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
    public function index(){
        $data = TicketOwnership::join('buyers', 'ticket_ownerships.buyer_id', '=','buyers.buyer_id')
                                ->join('seats', 'ticket_ownerships.seat_id', '=', 'seats.seat_id')
                                ->get();
        return view('sold', ['orders' => $data]);
    }

    public function indexSetAttend($unique){
        $seat = Seat::whereLink($unique)->first();
        $data = array();
        $data['warning']="aman";
        if($seat['ticket_status']=="attend"){
            $data['warning']="awas! sudah pernah discan";
        }
        else if($seat['ticket_status']=="exchanged"){
            $data['warning']="sudah tukar tiket";
        }
        else if($seat['ticket_status']=="notExchanged"){
            $data['warning']="belum tukar tiket";
        }
        $buyer = TicketOwnership::whereSeatId($seat['seat_id'])->first();
        $buyer = Buyer::whereBuyerId($buyer['buyer_id'])->first();
        $data['fname'] = $buyer['first_name'];
        $data['lname'] = $buyer['last_name'];
        $data['email'] = $buyer['email'];
        $data['seat'] = $seat['name'];
        $data['unique'] = $unique;
        return view('seatCheckout', ['data' => $data]);
    }

    public function setAttend(Request $request,$unique){
        $updateTo  = $request->query('updateTo');
        if($updateTo == "attend"){
            Seat::whereLink($unique)->update(['ticket_status' => "attend"]);
        }
        else if($updateTo == "exchangedModified"){
            Seat::whereLink($unique)->update(['ticket_status' => "exchangedModified"]);
        }
        else if($updateTo == "exchanged"){
            Seat::whereLink($unique)->update(['ticket_status' => "exchanged"]);
        }
        else if($updateTo == "notExchanged"){
            Seat::whereLink($unique)->update(['ticket_status' => "notExchanged"]);
        }

        return response($unique, Response::HTTP_OK);
    }



    public function seatInfo($unique){
        $seat = Seat::whereLink($unique)->first();
        $buyer = TicketOwnership::whereSeatId($seat['seat_id'])->first();
        $buyer = Buyer::whereBuyerId($buyer['buyer_id'])->first();
        $data = array();
        $data['fname'] = $buyer['first_name'];
        $data['lname'] = $buyer['last_name'];
        $data['email'] = $buyer['email'];
        $data['seat'] = $seat['name'];
        return view('seatInfo', ['data' => $data]);
    }

}
