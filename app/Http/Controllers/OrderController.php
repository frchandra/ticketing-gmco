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
            return "seat {$seatNameInRequest} was already taken, please go back and select another seat";
        }
        $affected = Seat::whereName($seatNameInRequest)->update(['is_reserved'=>Carbon::now()->timestamp+60*3]);//todo : mau berapa lama?
        if($affected < 1)
            return "cannot found seat {$seatNameInRequest}, please go back and select another seat";
        \DB::commit();
    }

    public function reserveIndex(){
        $seats = Seat::select(['name', 'is_reserved'])->get();
        return view('reserve', ["seats" => $seats]);
    }

    public function orderIndex(Request $request){ //todo ngeleboke rego kursi ning fe (uis)
        $seats['name'] = $request->session()->get('seatsNameInSession');
        if(!$seats['name']) {
            return "belum ngecim";
        }
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

        if($seatsNameInSession == null){
            foreach ($seatsNameInRequest as $seatNameInRequest) {
                $errorMsg = $this->storeReserveSeat($seatNameInRequest);if($errorMsg != null) return $errorMsg; //todo should use throwable
            }
            $request->session()->put('seatsNameInSession',  $seatsNameInRequest);
            return redirect('/ticketing/order');

        }
        else{
            foreach ($seatsNameInRequest as $seatNameInRequest){
                if(in_array($seatNameInRequest, $seatsNameInSession) == false){
                    $errorMsg = $this->storeReserveSeat($seatNameInRequest);if($errorMsg != null) return $errorMsg;
                }
            }
            $request->session()->put('seatsNameInSession',  $seatsNameInRequest);
            return redirect('/ticketing/order');
        }

    }
}
