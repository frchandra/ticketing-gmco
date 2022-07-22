<?php

namespace App\Http\Controllers;

use App\Models\OrderLog;
use App\Models\Seat;
use App\Models\TicketOwnership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class ResolveController extends Controller{
    public function index(){
        $orderLog = OrderLog::get();
        return $orderLog;
    }

    public function confirmOrder(Request $request){
        $seat_ids = $request->only('seat_id');
        foreach ($seat_ids['seat_id'] as $seat_id) {
            OrderLog::whereSeatId($seat_id)->update(['is_confirmed' => true]);
            TicketOwnership::create([
                'seat_id' => $seat_id,
                'buyer_id' => $request->get('buyer_id')
            ]);

        }
        return response($request->all(), Response::HTTP_CREATED);
    }

    //todo unconfirm order
}
