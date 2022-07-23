<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTicketRequest;
use App\Jobs\SendMailJob;
use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use function array_push;
use function implode;
use function redirect;
use function response;
use function var_dump;
use function view;


class OrderController extends Controller
{
    public function showToken(){
        echo csrf_token();
    }

    public function reserveIndex(){
        $seats = Seat::select(['name', 'is_reserved'])->get();
        return view('reserve', ["seats" => $seats]);
    }

    public function orderIndex(Request $request){
        $seats = $request->session()->get('seats');
        if(!$seats)return "belum melakukan cim";
        return view('order', ["seats" => $seats]);
    }

    public function reserveTicket(Request $request){
        $seats = $request->only('seat');
        $isHasSeatSession = $request->session()->get('seats');

        \DB::beginTransaction();
        foreach ($seats['seat'] as $seat) {
            if(Seat::whereName($seat)->value('is_reserved') > Carbon::now()->timestamp && !$isHasSeatSession){ //kursi udah ada yang ngecim
                \DB::rollBack();
                return "seat {$seat} already taken";
            }
            $affected = Seat::whereName($seat)->update(['is_reserved'=>Carbon::now()->timestamp+60]);//todo : mau berapa lama?
            if($affected < 1)return "cannot found seat {$seat}";
        }
        \DB::commit();

        $request->session()->put('seats', $seats);
//        return response($request->all(), Response::HTTP_CREATED);
        return redirect('/order');
    }

    public function orderTicket(OrderTicketRequest $request){
        $seats = $request->session()->get('seats');
        if(!$seats)return "belum melakukan cim";

        //constant declaration
        $time = Carbon::now()->timestamp;
        $case = 0;
        $conflictSeat = array();
        $purchasedSeat = array();

        Buyer::updateOrCreate($request->only('email'), $request->only('first_name', 'last_name', 'phone'));
        $buyer = Buyer::whereEmail($request->only('email'))->first();

        $email = $request->get('email');
        $path = $request->file('tf_proof')->storeAs('tf_proof', "{$email}_{$time}.png");

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
                'buyer_phone' => $buyer->phone,
                'buyer_fname' => $buyer->first_name,
                'seat_name' => $seat['name'],
                'price' => $seat['price'],
                'tf_proof' => $path,
                'is_confirmed' => false,
                'case' => $case
            ]);
            array_push($purchasedSeat, $seatName);
        }
        \DB::commit();

        $conflictSeatString = implode(", ", $conflictSeat);

        //send email
        $data = array();
        $data['email'] = $email;
        $data['email_type'] = 1;
        $this->dispatch(new SendMailJob($data));

        //send again
        $data['email_type'] = 2;
        $data['purchased'] = $purchasedSeat;
        $data['conflict'] = $conflictSeat;
        $this->dispatch(new SendMailJob($data));

        $request->session()->forget('seats');
        if($case == 1) return "anda kelamaan dalam proses transaksi, kursi ini telah di beli {$conflictSeatString}, silakan hubungi admin utk refund";
//        else return response($request->all(), Response::HTTP_CREATED);
        else return redirect('/reserve');
    }
}
