<?php

namespace App\Http\Service;

use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use function array_push;
use function config;
use function count;
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
        \Midtrans\Config::$isProduction = true;
        /**
         * Set sanitization on (default)
         */
        \Midtrans\Config::$isSanitized = true;
        /**
         * Set 3DS transaction for credit card to true
         */
        \Midtrans\Config::$is3ds = true;
        //todo(done): update url to env
        $paymentDetails["gopay"] = ["enable_callback" => true, "callback_url" => config('constants.APP_URL')];
        $paymentDetails["callbacks"] = ['finish' => config('constants.APP_URL')];
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

    public function upsertBuyer($userData, $userPhone, $seats){
        /**
         * get the seat that was purchased by this user, prevent the same user from buying more than n ticket
         */
        $buyerPreviousSeats = OrderLog::select('seat_name')->whereBuyerEmail($userData['email'])->get()->toArray();
        if(count($buyerPreviousSeats)+count($seats)<=5){
            /**
             * Upsert order request to DB
             */
            $buyer = Buyer::updateOrCreate($userData, $userPhone);
        }
        else{
            throw ValidationException::withMessages(["message" => "Jumlah pembelian tiket maksimal per orang adalah 5. Sudah " . count($buyerPreviousSeats) . " tiket/kursi yang terasosiasi dengan email ini. Silakan ulang pemesan menggunakan email yang berbeda"]);
        }
        /**
         * prevent the user from buying a seat/ticket that was sold (the confirmation status is settlement)
         */
        foreach ($seats as $seat){
            $isSeatSold = OrderLog::select('seat_name')->whereSeatName($seat)->where(function ($query) {
                $query->where("confirmation", "=", "settlement")->orWhere("confirmation", "=", "pending");
            })->first();
            if($isSeatSold){
                throw ValidationException::withMessages(["message" => "Maaf, kursi ini: {$isSeatSold['seat_name']} telah terbeli, silakan kembali ke halaman utama lalu memilih kursi lain"]);
            }
        }

        return $buyer;
    }

    public function prepareInvocation($buyer, $seats){
        $paymentDetails = array(); $purchasedSeats = array(); $gross_amount=0;
        $paymentDetails["transaction_details"] = ["order_id"=>Carbon::now()->timestamp, "gross_amount" => $gross_amount];
        $paymentDetails["customer_details"] = $buyer;
        $paymentDetails["item_details"] = array();
        /**
         * Prepare the helper variable
         */
        foreach ($seats as $seatName) {
            $seat = $this->createOrder($seatName, $paymentDetails);
            $gross_amount = $gross_amount + $seat['price'];
            $seatDetails = ["name" => $seatName, "price" => $seat['price'], "quantity" => 1, "id"=>$seatName];
            array_push($paymentDetails["item_details"], $seatDetails);
            array_push($purchasedSeats, $seatDetails["name"]);
        }
        $paymentDetails["transaction_details"]["gross_amount"] = $gross_amount;
        return ["paymentDetails" => $paymentDetails, 'purchasedSeats' => $purchasedSeats];

    }

}
