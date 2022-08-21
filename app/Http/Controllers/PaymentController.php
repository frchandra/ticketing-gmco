<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTicketRequest;
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
use function implode;
use function json_decode;
use function microtime;
use function rand;
use function redirect;
use Symfony\Component\HttpFoundation\Response;
use function response;
use function session_cache_expire;
use function sha1;
use function strtoupper;
use function substr;
use function var_dump;
use function view;



class PaymentController extends Controller{
    public function invokeMidtrans($paymentDetails){
        $key = config('midtrans.server_key');
        error_log($key);
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = $key;
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = $paymentDetails;

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return $snapToken;
    }

    public function orderTicket(OrderTicketRequest $request){
        $seats = $request->session()->get('seats');
        if(!$seats)return "belum melakukan cim";

        //constant declaration
        $case = 0; $gross_amount=0;
        $conflictSeat = array(); $paymentDetails = array(); $purchasedSeat = array();

        $buyer = Buyer::updateOrCreate($request->only('email'), $request->only('first_name', 'last_name', 'phone'));

        $paymentDetails["transaction_details"] = ["order_id"=>Carbon::now()->timestamp, "gross_amount" => $gross_amount];
        $paymentDetails["customer_details"] = $buyer->toArray();
        $paymentDetails["item_details"] = array();


        foreach ($seats['seat'] as $seatName) {
            \DB::beginTransaction();
            $seat = Seat::whereName($seatName)->first();
            if($seat['is_reserved'] !== 9999999999) { //if (telat?) dan yang penting masih kosong : proceed -> //store to log with (2:lucky) //return biasa
                $case = 2;
            }
            else{ //else: simpan sebagai log utk di resolve -> //store to log woth (1:to_late) -> //return pemberitahuan (bisa diakibatkan karena telat lantas keserobot)
                $case = 1;
                array_push($conflictSeat, $seatName);
            }

            //store to log
            Seat::whereSeatId($seat['seat_id'])->increment('is_reserved', 15*60);//update the value tambah waktulagi untuk membayar selama 15 menit
            OrderLog::create([
                'transaction_id' => $paymentDetails["transaction_details"]["order_id"],
                'buyer_id' => $buyer->buyer_id,
                'seat_id' => $seat['seat_id'],
                'buyer_email' => $request->get('email'),
                'buyer_phone' => $buyer->phone,
                'buyer_fname' => $buyer->first_name,
                'seat_name' => $seat['name'],
                'price' => $seat['price'],
                'vendor' => "havent_set",
                'confirmation' => "havent_set",
                'case' => $case
            ]);
            \DB::commit();
            $gross_amount = $gross_amount + $seat['price'];
            $seatDetails = ["name" => $seatName, "price" => $seat['price'], "quantity" => 1, "id"=>$seatName];
            array_push($paymentDetails["item_details"], $seatDetails);
            array_push($purchasedSeat, $seatDetails["name"]);
        }
        $paymentDetails["transaction_details"]["gross_amount"] = $gross_amount;
        $snapToken = $this->invokeMidtrans($paymentDetails);

        //send email
        $data = array();
        $data['email'] = $buyer->email;
        $data['email_type'] = 1;
        $this->dispatch(new SendMailJob($data));

        //send again to admin
        $data['email_type'] = 2;
        $data['purchased'] = $purchasedSeat;
        $data['conflict'] = $conflictSeat;
        $this->dispatch(new SendMailJob($data));

        $request->session()->forget('seats');
        $conflictSeatString = implode(", ", $conflictSeat);
        if($case == 1) return "anda kelamaan dalam proses transaksi, kursi ini telah di beli {$conflictSeatString}, silakan hubungi admin utk refund";
//        else return response($paymentDetails, Response::HTTP_CREATED);
        else return view("pay", ["snap_token" => $snapToken]);
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

        $seats = OrderLog::whereTransactionId($order_id)->get();
        foreach ($seats as $seat) {
            if($seat['confirmation'] != "settlement" && $seat['confirmation'] != "capture"){
                error_log("pembayaran belum selesai");
                return Response::HTTP_OK;
            }

            $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 6));
            \QrCode::size(300)->format('png')->generate(env('APP_URL')."/seat-info/{$uniqueKey}", "/var/www/storage/app/qr/{$seat['seat_name']}.png");
            Seat::whereName($seat['seat_name'])->update(['link' => $uniqueKey]);
            Seat::whereName($seat['seat_name'])->update(['is_reserved'=>9999999999]);
            TicketOwnership::updateOrCreate([
                'seat_id' => $seat['seat_id'],
                'buyer_id' => $seat['buyer_id']
            ]);
        }
        $buyer = Buyer::whereBuyerId($seat['buyer_id'])->first(); // whereTransactionId($order_id)->distinct()->get();
        $data = array();
        $data['first_name'] = $buyer['first_name'];
        $data['last_name'] = $buyer['last_name'];
        $data['seats'] = \Arr::pluck($seats->toArray(), 'seat_name');
        $data['email_type'] = 3; //3 confirm; 2 notify; 1 ack
        $data['email'] = $buyer['email'];

        $this->dispatch(new SendMailJob($data));

        return Response::HTTP_OK;
    }




}
