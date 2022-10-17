<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Http\Service\SeatService;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;



use function array_push;
use function config;
use function count;
use function in_array;
use function redirect;
use function response;
use function view;


class OrderController extends Controller{
    private $seatService;

    public function __construct(SeatService $seatService){
        $this->seatService = $seatService;
    }

    /**
     * @desc    Show the available seat on the booking page
     * @param   GET /v1/ticketing/booking
     * @return  seat availability
     * @scope   public
     */
    public function reserveIndex(Request $request){
        $seats = Seat::select(['name', 'is_reserved'])->get()->toArray();

        if(strpos($request->fullUrl(), "/api/v1")){
            $count = ["count" => count($seats)];
            foreach ($seats as &$seat) {
                if($seat['is_reserved']==config('constants.MAX_VALUE')){
                    $seat['is_reserved'] = 'sold/red';
                }
                else if($seat['is_reserved'] > Carbon::now()->timestamp){
                    $seat['is_reserved'] = 'booked/yellow';
                }
                else{
                    $seat['is_reserved']='available/green';
                }
            }
            array_push($seats, $count);
        }
        return response()->json($seats, 200);
    }
    /**
     * @desc    Handle seat booking request
     * @param   POST /v1/ticketing/booking
     * @return  the requested seat
     * @scope   public
     *
     * Note: for simulating conflict handling between 2 user booking the same seat please use 2 different browser window with different vendor
     * (i.e. mozilla and chrome). This is because, usually cookies and session is shared within multiple browser's window with the same vendor.
     * This will be messed up with the code's logic (because it's primarily using session & cookie as part of application logic) and you
     * may not be getting the result you expected.
     */
    public function reserveTicket(Request $request){
        $request->validate(['name' => 'required']);
        $seatsNameInRequest = $request->only('name')['name'];
        $seatsNameInSession = $request->session()->get('seatsNameInSession');
        
        // Seat::where("name", "=", "A14")->update(['is_reserved'=>Carbon::now()->timestamp+60*10]);
        // return "bug";

        /**
         * If the user haven't booked any seat yet
         */
        if($seatsNameInSession == null){
            if(count($seatsNameInRequest) >= 6){
                return response()->json(["status"=>"fail", "message" => "pemesanan maksimum 5 kursi"], 400);
            }
            /**
             * Store the seat booked by the user
             */
            foreach ($seatsNameInRequest as $seatNameInRequest) {
                try{
                    $this->seatService->storeSeatReservation($seatNameInRequest);
                }catch (ValidationException $e){
                    return $e->errors()['message'];
                }
            }
        }
        /**
         * If the user have booked one or more seats
         */
        else{
            foreach ($seatsNameInRequest as $seatNameInRequest){
                /**
                 * If the seat was not in session (the specific seat haven't booked by the user) then check the availability of that seat
                 */
                if(in_array($seatNameInRequest, $seatsNameInSession) == false){
                    try{
                        $this->seatService->storeSeatReservation($seatNameInRequest);
                    }catch (ValidationException $e){
                        return  $e->errors()['message'];
                    }
                }
            }
        }
        $request->session()->put('seatsNameInSession',  $seatsNameInRequest);
        return response()->json(["status"=>"success", "seatsName" => $seatsNameInRequest], 201);
    }

    /**
     * @desc    Show the seat order details and order form in order to filled by the user
     * @param   GET /v1/ticketing/order
     * @return  seats details
     * @scope   public that booked the seats
     */
    public function orderIndex(Request $request){
        $seats['name'] = $request->session()->get('seatsNameInSession');
        if(!$seats['name']) {
            return "anda belum melakukan pemilihan kursi";
        }
        $seats['price'] = array();
        foreach($seats['name'] as $seatsName) {
            $price = Seat::whereName($seatsName)->value('price');
            array_push($seats['price'], $price);
        }
        return response()->json($seats, 201);
    }
}
