<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTicketRequest;
use App\Jobs\SendMailJob;
use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            throw  ValidationException::withMessages(['message' => "seat {$seatNameInRequest} was already taken, please go back and select another seat"]);
        }
        $affected = Seat::whereName($seatNameInRequest)->update(['is_reserved'=>Carbon::now()->timestamp+60*3]);//todo : mau berapa lama?
        if($affected < 1)
            throw  ValidationException::withMessages(['message' => "cannot found seat {$seatNameInRequest}"]);
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
                try{
                    $this->storeReserveSeat($seatNameInRequest);
                }catch (\Exception $e){
                    return $e->errors()['message'];
                }

//                $errorMsg = $this->storeReserveSeat($seatNameInRequest);
//                if($errorMsg != null) return $errorMsg; //todo should use throwable
            }
//            $request->session()->put('seatsNameInSession',  $seatsNameInRequest);
//            return redirect('/ticketing/order');

        }
        else{
            foreach ($seatsNameInRequest as $seatNameInRequest){
                /**
                 * If the seat was not in session (the user have not booked it yet) then check the availability of that seat
                 */
                if(in_array($seatNameInRequest, $seatsNameInSession) == false){
                    try{
                        $this->storeReserveSeat($seatNameInRequest);
                    }catch (\Exception $e){
                        return  $e->errors()['message'];
                    }
                }
            }
        }
        $request->session()->put('seatsNameInSession',  $seatsNameInRequest);
        return redirect('/ticketing/order');
    }
}
