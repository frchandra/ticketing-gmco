<?php

namespace App\Http\Controllers;


use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use function var_dump;

class OrderController extends Controller
{
    public function showToken(){
        echo csrf_token();
    }

    public function reserveTicket(Request $request){
        $seats = $request->only('seat');
        \DB::beginTransaction();
        foreach ($seats['seat'] as $seat) {
            if(Seat::whereName($seat)->value('is_reserved') > Carbon::now()->timestamp){ //kursi udah ada yang ngecim
                \DB::rollBack();
                return "seat {$seat} already taken";
            }
            Seat::whereName($seat)->update(['is_reserved'=>Carbon::now()->timestamp+1800]);
        }
        \DB::commit();
        $request->session()->put('seats', $seats);
        return response($request->all(), Response::HTTP_CREATED);
    }

    public function orderTicket(Request $request){
        $seats = $request->session()->get('seats');
        Buyer::updateOrCreate($request->only('email'), $request->only('first_name', 'last_name', 'phone'));
        $buyer = Buyer::whereEmail($request->only('email'))->first();
        \DB::beginTransaction();
        foreach ($seats['seat'] as $seatName) {
            $seat = Seat::whereName($seatName)->first();
            if($seat['is_reserved'] < Carbon::now()->timestamp+1800){ //telat, session mu gak guna
                //remove session
                \DB::rollBack();
                return "to late";
            }
            if($seat['is_reserved'] < Carbon::now()->timestamp){ //belum ngecim
                //remove session
                \DB::rollBack();
                return "you must reserved the seat first";
            }
            if($seat['is_reserved'] == 9999999999){
               //remove session
                \DB::rollBack();
                return "seat {$seat['name']} is already taken";
            }

            Seat::whereSeatId($seat['seat_id'])->update(['is_reserved'=>9999999999]);
            OrderLog::create([
                'buyer_id' => $buyer->buyer_id,
                'seat_id' => $seat['seat_id'],
                'buyer_email' => $request->get('email'),
                'seat_name' => $seat['name'],
                'price' => $seat['price'],
                'tf_proof' => '#'
            ]);
        }
        \DB::commit();
        return $seats;
    }
}
