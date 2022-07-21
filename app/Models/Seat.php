<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Seat
 *
 * @property int $seat_id
 * @property string $name
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Seat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereSeatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Seat extends Model{
    use HasFactory;

    protected $guarded = [];

    public function buyer(){
        return $this->belongsToMany(Buyer::class, OrderLog::class);
    }
}
