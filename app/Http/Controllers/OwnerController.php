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
        if($seat['is_attend']){
            $data['warning']="awas! sudah pernah discan";
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
        Seat::whereLink($unique)->update(['is_attend' => true]);
        return response($unique, Response::HTTP_CREATED);
    }

    public function setUnAttend($name){
        Seat::whereName($name)->update(['is_attend' => false]);
        return response($name, Response::HTTP_OK);
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
