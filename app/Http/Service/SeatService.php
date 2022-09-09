<?php

namespace App\Http\Service;

use App\Models\Seat;
use App\Models\TicketOwnership;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SeatService{
    /**
     * Update seats availability to the current time + retention time
     * If is_reserved > current time then this seat is booked by another user
     * If is_reserved == 9999999999 then this seat is sold to another user
     * Else the seat is avalilable
     */
    public function storeSeatReservation($seatNameInReservation){
        \DB::beginTransaction();
        if(Seat::whereName($seatNameInReservation)->value('is_reserved') > Carbon::now()->timestamp){
            \DB::rollBack();
            throw  ValidationException::withMessages(['message' => "seat {$seatNameInReservation} was already taken, please go back and select another seat"]);
        }
        $affected = Seat::whereName($seatNameInReservation)->update(['is_reserved'=>Carbon::now()->timestamp+60*10]);//todo : mau berapa lama?
        if($affected < 1){
            \DB::rollBack();
            throw  ValidationException::withMessages(['message' => "seat {$seatNameInReservation} was already taken, please go back and select another seat"]);
        }
        \DB::commit();
    }

    public function updateSeatAvailability($seat, $uniqueKey){
        \DB::transaction();
        Seat::whereName($seat['seat_name'])->update(['link' => $uniqueKey]);
        Seat::whereName($seat['seat_name'])->update(['is_reserved'=>9999999999]);
        TicketOwnership::updateOrCreate([
            'seat_id' => $seat['seat_id'],
            'buyer_id' => $seat['buyer_id']
        ]);
        \DB::commit();
    }

}
