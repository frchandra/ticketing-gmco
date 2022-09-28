<?php

namespace App\Http\Service;

use App\Models\OrderLog;
use App\Models\Seat;
use function array_push;
use function config;
use function error_log;
use function implode;

class PaymentService{
    public function invokeMidtrans($paymentDetails){
        $key = config('midtrans.server_key');
        error_log($key);
        /**
         * Set your Merchant Server Key
         */
        \Midtrans\Config::$serverKey = $key;
        /**
         * Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
         */
        \Midtrans\Config::$isProduction = false;
        /**
         * Set sanitization on (default)
         */
        \Midtrans\Config::$isSanitized = true;
        /**
         * Set 3DS transaction for credit card to true
         */
        \Midtrans\Config::$is3ds = true;

        $paymentDetails["gopay"] = ["enable_callback" => true, "callback_url" => "http://127.0.0.1"];
        $paymentDetails["shopeepay"] = ["callback_url" => "http://127.0.0.1"];
        $paymentDetails["callbacks"] = ['finish' => "http://127.0.0.1"];
        $snapToken = \Midtrans\Snap::getSnapToken($paymentDetails);

        return $snapToken;
    }

    public function createOrder($seatName, $paymentDetails){
        \DB::beginTransaction();
        $seat = Seat::whereName($seatName)->first();
        Seat::whereSeatId($seat['seat_id'])->increment('is_reserved', config('constants.TRANSACTION_COMPLETION_DURATION')*60);
        OrderLog::create([
            'transaction_id' => $paymentDetails["transaction_details"]["order_id"],
            'buyer_id' => $paymentDetails["customer_details"]->buyer_id,
            'seat_id' => $seat['seat_id'],
            'buyer_email' => $paymentDetails["customer_details"]->email,
            'buyer_phone' => $paymentDetails["customer_details"]->phone,
            'buyer_fname' => $paymentDetails["customer_details"]->first_name,
            'seat_name' => $seat['name'],
            'price' => $seat['price'],
            'vendor' => "havent_set",
            'confirmation' => "havent_set",
        ]);
        \DB::commit();
        return $seat;
    }
}
