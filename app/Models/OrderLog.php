<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderLog
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
