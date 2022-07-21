<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Buyer
 *
 * @property int $buyer_id
 * @property string $email
 * @property string $phone
 * @property string $first_name
 * @property string $last_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\BuyerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Buyer extends Model{
    use HasFactory;

    protected $guarded = [];

    public function orderLog(){
        return $this->belongsToMany(Seat::class, OrderLog::class);
    }
}
