<?php

namespace App\Http\Service;

use App\Jobs\SendMailJob;
use App\Models\Buyer;

class EmailService{
    public function sentAckToUser($buyer){
        $data = array();
        $data['email'] = $buyer->email;
        $data['email_type'] = 1;
        $this->dispatch(new SendMailJob($data));
    }

    public function sentNotificationToAdmin($purchasedSeat){
        $data['email_type'] = 2;
        $data['purchased'] = $purchasedSeat;
        $this->dispatch(new SendMailJob($data));
    }

    public function sentConfirmationToUser($buyer, $seats){
        $data = array();
        $data['first_name'] = $buyer['first_name'];
        $data['last_name'] = $buyer['last_name'];
        $data['seats'] = \Arr::pluck($seats->toArray(), 'seat_name');
        $data['email_type'] = 3; //3 confirm; 2 notify; 1 ack
        $data['email'] = $buyer['email'];
        $this->dispatch(new SendMailJob($data));
    }
}
