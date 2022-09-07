<?php

namespace App\Http\Controllers;


use App\Models\OrderLog;

use function count;

use function view;

class ResolveController extends Controller{
    public function index(){
        $buyers = OrderLog::select(['transaction_id', 'buyer_email', 'buyer_phone', 'buyer_fname', 'vendor', 'confirmation']) ->distinct()->get();
        foreach ($buyers as $buyer) {
            $seats = OrderLog::select(['seat_name'])->where('transaction_id', '=', $buyer['transaction_id'])->where('confirmation', '!=', 'settlement||capture')->get();
            $total = OrderLog::select(['price'])->where('transaction_id', '=', $buyer['transaction_id'])->where('confirmation', '!=', 'settlement||capture')->sum('price');
            $buyer['seatsCount'] = count($seats);
            $buyer['seats'] = $seats->pluck('seat_name');
            $buyer['price'] = $total;
        }
        return view('resolve', ['orders' => $buyers]);
    }
}
