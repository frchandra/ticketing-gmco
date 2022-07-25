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
 * @property int $is_attend
 * @property int $is_reserved
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Buyer[] $buyers
 * @property-read int|null $buyers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereIsAttend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereIsReserved($value)
 * @property string $link
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereLink($value)
 */
class Seat extends Model{
    use HasFactory;

    protected $guarded = [];

    public function buyers(){
        return $this->belongsToMany(Buyer::class, OrderLog::class);
    }
}
