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
use function array_merge;
use function array_merge_recursive_distinct;
use function array_push;
use function array_unique;
use function implode;
use function in_array;
use function redirect;
use function response;
use function var_dump;
use function version_compare;
use function view;


class OrderController extends Controller{
    public function storeReserveSeat($seatNameInRequest){
        \DB::beginTransaction();
        if(Seat::whereName($seatNameInRequest)->value('is_reserved') > Carbon::now()->timestamp){
            \DB::rollBack();
            return "seat {$seatNameInRequest} already taken";
        }
        $affected = Seat::whereName($seatNameInRequest)->update(['is_reserved'=>Carbon::now()->timestamp+60*10]);//todo : mau berapa lama?
        if($affected < 1)return "cannot found seat {$seatNameInRequest}";
        \DB::commit();
    }

    public function showToken(){
        echo csrf_token();
    }

    public function reserveIndex(){
        $seats = Seat::select(['name', 'is_reserved'])->get();
        return view('reserve', ["seats" => $seats]);
    }

    public function orderIndex(Request $request){ //todo ngeleboke rego kursi ning fe (uis)
        $seats['name'] = $request->session()->get('seatsNameInSession');
        if(!$seats) { return "belum ngecim";}
        $seats['price'] = array();
        foreach($seats['name'] as $seatsName) {
            $price = Seat::whereName($seatsName)->value('price');
            array_push($seats['price'], $price);
        }
        return view('order', ["seats" => $seats]);
    }

    public function reserveTicket(Request $request){
        $request->validate(['seat' => 'required']);
        $seatsNameInRequest = $request->only('seat')['seat'];
        $seatsNameInSession = $request->session()->get('seatsNameInSession');
        $errorMsg = "";

        if($seatsNameInSession == null){
            foreach ($seatsNameInRequest as $seatNameInRequest) {
                $errorMsg = $this->storeReserveSeat($seatNameInRequest);if($errorMsg != null) return $errorMsg;
            }
            $request->session()->put('seatsNameInSession',  $seatsNameInRequest);
//            return $seatsNameInSession;
            return redirect('/order');

        }
        else{
            foreach ($seatsNameInRequest as $seatNameInRequest){
                if(in_array($seatNameInRequest, $seatsNameInSession) == false){
                    $errorMsg = $this->storeReserveSeat($seatNameInRequest);if($errorMsg != null) return $errorMsg;
                }
            }
            $request->session()->put('seatsNameInSession', array_unique(array_merge($seatsNameInSession, $seatsNameInRequest)));
//            return $seatsNameInSession;
            return redirect('/order');
        }

    }

    public function orderTicket(OrderTicketRequest $request){
        $seats = $request->session()->get('seatsNameInSession');
        if(!$seats)return "belum melakukan cim";

        //constant declaration
        $time = Carbon::now()->timestamp;
        $case = 0;
        $conflictSeat = array();
        $purchasedSeat = array();

        $buyer = Buyer::updateOrCreate($request->only('email'), $request->only('first_name', 'last_name', 'phone'));

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

        //send again to admin
        $data['email_type'] = 2;
        $data['purchased'] = $purchasedSeat;
        $data['conflict'] = $conflictSeat;
        $this->dispatch(new SendMailJob($data));

//        $request->session()->forget('seats');
        if($case == 1) return "anda kelamaan dalam proses transaksi, kursi ini telah di beli {$conflictSeatString}, silakan hubungi admin utk refund";
//        else return response($request->all(), Response::HTTP_CREATED);
        else return redirect('/reserve');
    }
}
