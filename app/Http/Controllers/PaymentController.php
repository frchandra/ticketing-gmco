<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTicketRequest;
use App\Http\Service\EmailService;
use App\Http\Service\PaymentService;
use App\Http\Service\SeatService;
use App\Models\Buyer;
use App\Models\OrderLog;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use function array_push;
use function config;
use function env;
use function error_log;

use function json_decode;
use function microtime;
use function rand;
use Symfony\Component\HttpFoundation\Response;
use function rescue;
use function response;
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
     * @desc    Creating order request then issuing midtrans API then sent email notificaation to user and admin
     * @param   POST /v1/ticketing/order
     * @return  snap token
     * @scope   public that booked the seats
     */
    public function orderTicket(OrderTicketRequest $request){
        $seats = $request->session()->get('seatsNameInSession');
        if(!$seats){
            return response()->json(["message" => "anda belum memilih kursi, silakan memilih kursi terlebih dahulu"], 401);
        }
        /**
         * upsert new user, if the user already bought >5 ticket then return error
         */
        try{
            $buyer = $this->paymentService->upsertBuyer($request->only('email', 'first_name', 'last_name'), $request->only('phone'), $seats);
        }catch (ValidationException $e){
            return response()->json(["status"=>"fail", "message" => $e->errors()['message']], 400);
        }
        /**
         * Helper variable declaration
         */
        $invocationData = $this->paymentService->prepareInvocation($buyer, $seats);
        $snapToken = $this->paymentService->invokeMidtrans($invocationData["paymentDetails"]);
        /**
         * Send Ack email to user
         */
        $this->emailService->sentAckToUser($buyer);
        /**
         * Send notification email to the admin
         */
        $this->emailService->sentNotificationToAdmin($invocationData["purchasedSeats"], $buyer);

        return response()->json($snapToken, 201);
//        return view("pay", ["snap_token" => $snapToken]);

    }


    /**
     *
     */
    public function callbackHandler(Request $request){
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;

        /**
         * Check response validity from midtran's response using algorithm defined on Midtran's docs <- feel free to check this docs
         */
        $json = json_decode($request->getContent());
        $signature_key = hash('sha512',$json->order_id . $json->status_code . $json->gross_amount . config('midtrans.server_key'));
        if($signature_key != $json->signature_key){
            error_log('invalid signature key at transaction'.$order_id ."signature key :: " . $signature_key . "provided ::  ". $json->signature_key);
            return Response::HTTP_OK;
        }
        /**
         * Check seat validity from midtrans response
         */
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

            $this->seatService->updateSeatAvailability($seat, $uniqueKey);
        }

        /**
         * Transaction status is sent from midtrans API service
         * see "transaction status" from midtran's docs
         */
        if($transaction == "settlement"){
            $buyer = Buyer::whereBuyerId($seat['buyer_id'])->first();
            $this->emailService->sentConfirmationToUser($buyer, $seats);
        }
        return Response::HTTP_OK;
    }
}
