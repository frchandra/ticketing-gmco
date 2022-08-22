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
use function array_push;
use function config;
use function count;
use function env;
use function redirect;
use function response;
use function var_dump;
use function view;

class ResolveController extends Controller{
    public function index(){
        $buyers = OrderLog::select(['transaction_id', 'buyer_email', 'buyer_phone', 'buyer_fname', 'vendor', 'confirmation']) ->distinct()->get();
        foreach ($buyers as $buyer) {
            $seats = OrderLog::select(['seat_name'])->where('transaction_id', '=', $buyer['transaction_id'])->where('confirmation', '!=', 'settlement||capture')->get();
            $total = OrderLog::select(['price'])->where('transaction_id', '=', $buyer['transaction_id'])->where('confirmation', '!=', 'settlement||capture')->sum('price');
            $buyer['seatsCount'] = count($seats);
            $buyer['seats'] = $seats->pluck('seat_name');
            $buyer['price'] = $total;
        }
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
        $request->validate([
            'seat_name' => 'required'
        ]);
        $seat_names = $request->only('seat_name');
        foreach ($seat_names['seat_name'] as $seat_name) {

            if(OrderLog::whereSeatName($seat_name)->value('is_confirmed') == true){
                return "udah ada admin yang nge acknowledge, proses dibatalkan";
            }
            OrderLog::whereSeatName($seat_name)->update(['is_confirmed' => true]);

            $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 6));
            \QrCode::size(300)->format('png')->generate(env('APP_URL')."/seat-info/{$uniqueKey}", "/var/www/storage/app/qr/{$seat_name}.png");
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

        $this->dispatch(new SendMailJob($data));
//        return response($request->all(), Response::HTTP_CREATED);
        return redirect('/resolve');
    }


    //todo unconfirm order
}
