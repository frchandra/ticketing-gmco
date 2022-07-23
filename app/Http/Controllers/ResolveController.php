<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Models\Buyer;
use App\Models\OrderLog;
use App\Models\Seat;
use App\Models\TicketOwnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use function app;
use function array_push;
use function count;
use function redirect;
use function response;
use function var_dump;
use function view;

class ResolveController extends Controller{
    public function index(){
        $buyers = OrderLog::select(['buyer_email', 'buyer_phone', 'buyer_fname', 'tf_proof', 'buyer_id'])->distinct()->where('is_confirmed', '=', 'false')->get();
//        return $buyers;
        foreach ($buyers as $buyer) {
            $seats = OrderLog::select(['seat_name'])->where('tf_proof', '=', $buyer['tf_proof'])->get();
            $total = OrderLog::where('tf_proof', '=', $buyer['tf_proof'])->sum('price');
            $temp = array();
            foreach ($seats as $seat)
                array_push($temp, $seat['seat_name']);
            $buyer['seatsCount'] = count($temp);
            $buyer['seats'] = $temp;
            $buyer['price'] = $total;

        }
//        return $buyers;
        return view('resolve', ['orders' => $buyers]);
    }


    public function showTf($path){
        $path = "/tf_proof/{$path}";
        if (!Storage::exists($path)) {
            return \Response::make('File no found.', 404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);
        $response = \Response::make($file, 200)->header("Content-Type", $type);
        return $response;
    }

    public function confirmOrder(Request $request){
//        return $request;
        $seat_names = $request->only('seat_name');
        foreach ($seat_names['seat_name'] as $seat_name) {
            OrderLog::whereSeatName($seat_name)->update(['is_confirmed' => true]);
            $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 6));
            Seat::whereName($seat_name)->update(['link' => $uniqueKey]);

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
        $data['email'] = $buyer['email'];
        //todo sent email to the 'winning' buyers and generate qr
        $this->dispatch(new SendMailJob($data));
//        return response($request->all(), Response::HTTP_CREATED);
        return redirect('/resolve');
    }


    //todo unconfirm order
}
