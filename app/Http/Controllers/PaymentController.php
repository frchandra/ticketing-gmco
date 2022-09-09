<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTicketRequest;
use App\Http\Service\EmailService;
use App\Http\Service\PaymentService;
use App\Http\Service\SeatService;
use App\Jobs\SendMailJob;
use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use App\Models\TicketOwnership;

use Carbon\Carbon;
use Illuminate\Http\Request;
use function array_push;
use function config;
use function env;
use function error_log;

use function json_decode;
use function microtime;
use function rand;
use Symfony\Component\HttpFoundation\Response;
use function sha1;
use function strtoupper;
use function substr;
use function view;



class PaymentController extends Controller{
    private $paymentService;
    private $emailService;
    private $seatService;

    public function __construct(PaymentService $paymentService, EmailService $emailService, SeatService $seatService){
        $this->paymentService = $paymentService;
        $this->emailService = $emailService;
        $this->seatService = $seatService;
    }

    /**
     * Creating order request, issuing midtrans, sent email to user and admin
     */
    public function orderTicket(OrderTicketRequest $request){
        $seats = $request->session()->get('seatsNameInSession');
        if(!$seats){
            return "anda belum memilih kursi, silakan memilih kursi terlebih dahulu";
        }
        /**
         * Helper variable declaration
         */
        $paymentDetails = array(); $purchasedSeat = array(); $gross_amount=0;
        /**
         * Upsert order request to DB
         */
        $buyer = Buyer::updateOrCreate($request->only('email','first_name', 'last_name'), $request->only('phone'));
        /**
         * Helper variable declaration
         */
        $paymentDetails["transaction_details"] = ["order_id"=>Carbon::now()->timestamp, "gross_amount" => $gross_amount];
        $paymentDetails["customer_details"] = $buyer;
        $paymentDetails["item_details"] = array();

        foreach ($seats as $seatName) {
            $seat = $this->paymentService->createOrder($seatName, $paymentDetails);
            $gross_amount = $gross_amount + $seat['price'];
            $seatDetails = ["name" => $seatName, "price" => $seat['price'], "quantity" => 1, "id"=>$seatName];
            array_push($paymentDetails["item_details"], $seatDetails);
            array_push($purchasedSeat, $seatDetails["name"]);
        }
        $paymentDetails["transaction_details"]["gross_amount"] = $gross_amount;
        $snapToken = $this->paymentService ->invokeMidtrans($paymentDetails);

        //send email to user
//        $data = array();
//        $data['email'] = $buyer->email;
//        $data['email_type'] = 1;
//        $this->dispatch(new SendMailJob($data));
        $this->emailService->sentAckToUser($buyer);

        //send again to admin
//        $data['email_type'] = 2;
//        $data['purchased'] = $purchasedSeat;
//        $this->dispatch(new SendMailJob($data));
        $this->emailService->sentNotificationToAdmin($purchasedSeat);

        return view("pay", ["snap_token" => $snapToken]);
    }

    public function callbackHandler(Request $request){
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;

        $json = json_decode($request->getContent());
        $signature_key = hash('sha512',$json->order_id . $json->status_code . $json->gross_amount . config('midtrans.server_key'));
        if($signature_key != $json->signature_key){
            error_log('invalid signature key at transaction'.$order_id ."signature key :: " . $signature_key . "provided ::  ". $json->signature_key);
            return Response::HTTP_OK;
        }
        $affected = OrderLog::whereTransactionId($order_id)->update(['confirmation' => $transaction, 'vendor' => $type]);
        if(!$affected){
            error_log('no transaction id found at'.$order_id);
            return Response::HTTP_OK;
        }

        /**
         * Update seat availability information for each seat the user pay
         */
        $seats = OrderLog::whereTransactionId($order_id)->get();
        foreach ($seats as $seat) {
            /**
             * Create QR code for each seat
             */
            $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 6));
            \QrCode::size(300)->format('png')->generate(env('APP_URL')."/seat-info/{$uniqueKey}", "/var/www/storage/app/qr/{$seat['seat_name']}.png");

//            \DB::transaction();
//            Seat::whereName($seat['seat_name'])->update(['link' => $uniqueKey]);
//            Seat::whereName($seat['seat_name'])->update(['is_reserved'=>9999999999]);
//            TicketOwnership::updateOrCreate([
//                'seat_id' => $seat['seat_id'],
//                'buyer_id' => $seat['buyer_id']
//            ]);
//            \DB::commit();

            $this->seatService->updateSeatAvailability($seat, $uniqueKey);


        }


        if($transaction == "settlement"){
            $buyer = Buyer::whereBuyerId($seat['buyer_id'])->first(); // whereTransactionId($order_id)->distinct()->get();

//            $data = array();
//            $data['first_name'] = $buyer['first_name'];
//            $data['last_name'] = $buyer['last_name'];
//            $data['seats'] = \Arr::pluck($seats->toArray(), 'seat_name');
//            $data['email_type'] = 3; //3 confirm; 2 notify; 1 ack
//            $data['email'] = $buyer['email'];
//            $this->dispatch(new SendMailJob($data));

            $this->emailService->sentConfirmationToUser($buyer, $seats);
        }


        return Response::HTTP_OK;
    }




}
