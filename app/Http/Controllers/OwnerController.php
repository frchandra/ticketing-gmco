<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Seat;
use App\Models\TicketOwnership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;
use function view;

class OwnerController extends Controller{
    /**
     * helper function to prepare the data about a particular seat
     */
    private function setUserData($seat, $unique){
        \DB::beginTransaction();
        $buyer = TicketOwnership::whereSeatId($seat['seat_id'])->first();
        $buyer = Buyer::whereBuyerId($buyer['buyer_id'])->first();
        \DB::commit();
        $data['fname'] = $buyer['first_name'];
        $data['lname'] = $buyer['last_name'];
        $data['email'] = $buyer['email'];
        $data['seat'] = $seat['name'];
        $data['unique'] = $unique;
        return $data;
    }

    /**
     * Showing default page containing sold ticket
     */
    public function index(){
        $data = TicketOwnership::join('buyers', 'ticket_ownerships.buyer_id', '=','buyers.buyer_id')
                                ->join('seats', 'ticket_ownerships.seat_id', '=', 'seats.seat_id')
                                ->get();
        return view('sold', ['orders' => $data]);
    }

    /**
     *  Displaying the seat data to the admin user
     *  - attend: the user is attending the event
     *  - exchanged : the user already exchanged the e-ticket with real ticket but not necessarily attending the event
     *  - notExchanged : the user hasn't exhcange the e-ticket
     */
    public function indexSetAttend($unique){
        $seat = Seat::whereLink($unique)->first();
        if(!$seat){
            return "kursi ini tidak terdaftar pada sistem (tidak memiliki qr-code)";
        }
        $data = $this->setUserData($seat, $unique);
        $data['warning']="aman";
        if($seat['ticket_status']=="attend"){
            $data['warning']="awas! sudah pernah discan";
        }
        else if($seat['ticket_status']=="exchangedNotAttend"){
            $data['warning']="sudah tukar tiket";
        }
        else if($seat['ticket_status']=="notExchanged"){
            $data['warning']="belum tukar tiket";
        }
        return view('seatCheckout', ['data' => $data]);
    }

    public function setAttend(Request $request,$unique){
        $updateTo  = $request->only('updateTicketStatus')['updateTicketStatus'];
        if($updateTo == "attend"){
            \DB::beginTransaction();
            Seat::whereLink($unique)->update(['ticket_status' => "attend"]);
            \DB::commit();
        }
        else if($updateTo == "exchangedModified"){
            \DB::beginTransaction();
            Seat::whereLink($unique)->update(['ticket_status' => "exchangedModified"]);
            \DB::commit();
        }
        else if($updateTo == "exchangedNotAttend"){
            \DB::beginTransaction();
            Seat::whereLink($unique)->update(['ticket_status' => "exchangedNotAttend"]);
            \DB::commit();
        }
        else if($updateTo == "notExchanged"){
            \DB::beginTransaction();
            Seat::whereLink($unique)->update(['ticket_status' => "notExchanged"]);
            \DB::commit();
        }

        return response($unique, Response::HTTP_OK);
    }


    /**
     * Displaying the seat data to the user
     */
    public function seatInfo($unique){


        $seat = Seat::whereLink($unique)->first();

        if(!$seat){
            return response()->json(["status"=>"fail", "message"=>"maaf, kursi dengan kode unik ini tidak ada"], 400);
        }
        $data = $this->setUserData($seat, $unique);
        return view('seatInfo', ['data' => $data]);
    }

}
