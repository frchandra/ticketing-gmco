<?php

namespace App\Http\Service;

use App\Models\Seat;
use Carbon\Carbon;

class SeatService{
    public function storeSeatReservation($seatNameInReservation){
        \DB::beginTransaction();
        if(Seat::whereName($seatNameInReservation)->value('is_reserved') > Carbon::now()->timestamp){
            \DB::rollBack();
            return "seat {$seatNameInReservation} was already taken, please go back and select another seat";
        }
        $affected = Seat::whereName($seatNameInReservation)->update(['is_reserved'=>Carbon::now()->timestamp+60*3]);//todo : mau berapa lama?
        if($affected < 1)
            return "cannot found seat {$seatNameInReservation}, please go back and select another seat";
        \DB::commit();
    }

}
