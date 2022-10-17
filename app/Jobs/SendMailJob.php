<?php

namespace App\Jobs;

use App\Mail\AckMail;
use App\Mail\ConfirmMail;
use App\Mail\NotifMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;


class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data){
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        if($this->data['email_type'] == 3){
            $confirmMail = new ConfirmMail($this->data);
            Mail::to($this->data['email'])->send($confirmMail);
        }
        if($this->data['email_type'] == 2){
            $notifMail = new NotifMail($this->data);
            Mail::to('gmcolive@gmail.com')->send($notifMail);
        }
        if($this->data['email_type'] == 1){
            $ackMail = new AckMail($this->data);
            Mail::to($this->data['email'])->send($ackMail);
        }

    }
}
