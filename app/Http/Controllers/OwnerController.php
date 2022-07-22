<?php

namespace App\Http\Controllers;

use App\Models\OrderLog;
use App\Models\Seat;
use App\Models\TicketOwnership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerController extends Controller{
    public function index(){
        $data = TicketOwnership::join('buyers', 'ticket_ownerships.buyer_id', '=','buyers.buyer_id')
                                ->join('seats', 'ticket_ownerships.seat_id', '=', 'seats.seat_id')
                                ->get();
        return $data;
    }

    public function setAttend($id){
        Seat::whereSeatId($id)->update(['is_attend' => true]);
        return response($id, Response::HTTP_CREATED);
    }
}
