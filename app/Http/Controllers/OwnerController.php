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
        $buyer = TicketOwnership::whereSeatId($seat['seat_id'])->first();
        $buyer = Buyer::whereBuyerId($buyer['buyer_id'])->first();
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
            Seat::whereLink($unique)->update(['ticket_status' => "attend"]);
        }
        else if($updateTo == "exchangedModified"){
            Seat::whereLink($unique)->update(['ticket_status' => "exchangedModified"]);
        }
        else if($updateTo == "exchangedNotAttend"){
            Seat::whereLink($unique)->update(['ticket_status' => "exchangedNotAttend"]);
        }
        else if($updateTo == "notExchanged"){
            Seat::whereLink($unique)->update(['ticket_status' => "notExchanged"]);
        }

        return response($unique, Response::HTTP_OK);
    }


    /**
     * Displaying the seat data to the user
     */
    public function seatInfo($unique){
        $seat = Seat::whereLink($unique)->first();
        $data = $this->setUserData($seat, $unique);
        return view('seatInfo', ['data' => $data]);
    }

}
