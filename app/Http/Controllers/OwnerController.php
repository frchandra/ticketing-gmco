<?php

namespace App\Http\Controllers;

use App\Models\TicketOwnership;
use Illuminate\Http\Request;

class OwnerController extends Controller{
    public function index(){
        $data = TicketOwnership::join('buyers', 'ticket_ownerships.buyer_id', '=','buyers.buyer_id')
                                ->join('seats', 'ticket_ownerships.seat_id', '=', 'seats.seat_id')
                                ->get();
        return $data;
    }
}
