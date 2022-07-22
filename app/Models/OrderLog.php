<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderLoglog
 *
 * @property int $orderLog_id
 * @property int $buyer_id
 * @property int $seat_id
 * @property string $tf_proof
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereOrderLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereSeatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereTfProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $buyer_email
 * @property string $seat_name
 * @property int $price
 * @property int $is_confirmed
 * @property int $case
 * @property-read \App\Models\Buyer $buyer
 * @property-read \App\Models\Seat $seat
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereBuyerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereIsConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLog whereSeatName($value)
 */
class OrderLog extends Model{
    use HasFactory;

    protected $guarded = [];

    public function buyer(){
        return $this->belongsTo(Buyer::class, 'buyer_id', 'buyer_id');
    }

    public function seat(){
        return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
    }
}
