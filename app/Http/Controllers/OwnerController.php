<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\OrderLog;
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
        if($seat['attendStatus']==2){
            $data['warning']="awas! sudah pernah discan";
        }
        else if($seat['attendStatus']==1){
            $data['warning']="sudah tukar tiket";
        }
        else if($seat['attendStatus']==0){
            $data['warning']="belum tukar tiket";
        }
        $buyer = TicketOwnership::whereSeatId($seat['seat_id'])->first();
        $buyer = Buyer::whereBuyerId($buyer['buyer_id'])->first();
        $data['fname'] = $buyer['first_name'];
        $data['lname'] = $buyer['last_name'];
        $data['email'] = $buyer['email'];
        $data['seat'] = $seat['name'];
        $data['unique'] = $unique;
//        return $data;
        return view('seatCheckout', ['data' => $data]);
    }

    public function setAttend($unique){
        Seat::whereLink($unique)->update(['attendStatus' => 2]);
        return response($unique, Response::HTTP_CREATED);
    }

    public function setNotAttend($unique){
        Seat::whereLink($unique)->update(['attendStatus' => 1]);
        return response($unique, Response::HTTP_OK);
    }

    public function setGetTicket($unique){
        Seat::whereLink($unique)->update(['attendStatus' => 1]);
        return response($unique, Response::HTTP_OK);
    }

    public function setNotGetTicket($unique){
        Seat::whereLink($unique)->update(['attendStatus' => 0]);
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
//        return response($unique, Response::HTTP_OK);
        return view('seatInfo', ['data' => $data]);
    }

}
