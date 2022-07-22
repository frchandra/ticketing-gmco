<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TicketOwnership
 *
 * @property int $ticketOwnership_id
 * @property int $buyer_id
 * @property int $seat_id
 * @property string $user_email
 * @property string $seat_name
 * @property string $seat_price
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereSeatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereSeatName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereSeatPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereTicketOwnershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketOwnership whereUserEmail($value)
 * @mixin \Eloquent
 */
class TicketOwnership extends Model{
    use HasFactory;

    protected $guarded = [];
}
