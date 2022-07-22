<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use App\Models\TicketOwnership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function array_push;
use function response;

class ResolveController extends Controller{
    public function index(){
        $orderLog = OrderLog::get();
        return $orderLog;
    }

    public function confirmOrder(Request $request){
        $seat_names = $request->only('seat_name');
        foreach ($seat_names['seat_name'] as $seat_name) {
            OrderLog::whereSeatName($seat_name)->update(['is_confirmed' => true]);
            $seat = OrderLog::whereSeatName($seat_name)->first();
            TicketOwnership::create([
                'seat_id' => $seat['seat_id'],
                'buyer_id' => $request->get('buyer_id')
            ]);
        }
        $buyer = Buyer::whereBuyerId($request->get('buyer_id'))->first();
        $data = array();
        $data['first_name'] = $buyer['first_name'];
        $data['last_name'] = $buyer['last_name'];
        $data['seats'] = $seat_names['seat_name'];
        $data['email_type'] = 3; //3 confirm; 2 notify; 1 ack
        //todo sent email to the 'winning' buyers and generate qr
        $this->dispatch(new SendMailJob($data));
        return response($request->all(), Response::HTTP_CREATED);
    }

    //todo unconfirm order
}
