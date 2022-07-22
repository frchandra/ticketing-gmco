<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTicketRequest;
use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use function array_push;
use function implode;
use function response;
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
            $affected = Seat::whereName($seat)->update(['is_reserved'=>Carbon::now()->timestamp+60]);//todo : mau berapa lama?
            if($affected < 1)return "cannot found seat {$seat}";
        }
        \DB::commit();

        $request->session()->put('seats', $seats);
        return response($request->all(), Response::HTTP_CREATED);
    }

    public function orderTicket(OrderTicketRequest $request){
        $seats = $request->session()->get('seats');
        if(!$seats)return "belum melakukan cim";

        Buyer::updateOrCreate($request->only('email'), $request->only('first_name', 'last_name', 'phone'));
        $buyer = Buyer::whereEmail($request->only('email'))->first();
        $email = $request->get('email');
        $time = Carbon::now();
        $path = $request->file('tf_proof')->storeAs('tf_proof', "{$email}_{$time}.png");
        $case = 0;
        $conflictSeat = array();

        \DB::beginTransaction();
        foreach ($seats['seat'] as $seatName) {
            $seat = Seat::whereName($seatName)->first();
            if($seat['is_reserved'] !== 9999999999) { //if (telat?) dan yang penting masih kosong : proceed -> //store to log with (2:lucky) //return biasa
                $case = 2;
            }
            else{ //else: simpan sebagai log utk di resolve -> //store to log woth (1:to_late) -> //return pemberitahuan (bisa diakibatkan karena telat lantas keserobot)
                $case = 1;
                array_push($conflictSeat, $seatName);
            }


            //store to log
            Seat::whereSeatId($seat['seat_id'])->update(['is_reserved'=>9999999999]);
            OrderLog::create([
                'buyer_id' => $buyer->buyer_id,
                'seat_id' => $seat['seat_id'],
                'buyer_email' => $request->get('email'),
                'seat_name' => $seat['name'],
                'price' => $seat['price'],
                'tf_proof' => $path,
                'is_confirmed' => false,
                'case' => $case
            ]);
        }
        \DB::commit();
        $request->session()->forget('seats');
        $conflictSeatString = implode(", ", $conflictSeat);
        if($case == 1) return "anda kelamaan dalam proses transaksi, kursi ini telah di beli {$conflictSeatString}, silakan hubungi admin utk refund";
//        else if($case ==2)return "case2";
        else return response($request->all(), Response::HTTP_CREATED);
    }
}
