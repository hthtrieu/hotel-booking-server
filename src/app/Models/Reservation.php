<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends BaseModel
{
    protected $fillable = [
        'email',
        'site_fees',
        'tax_paid',
        'status',
        'total_price',
    ];
    protected $casts = [
        'status' => ReservationStatusEnum::class,
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_reserveds', 'reservation_id')
            ->withPivot(['start_day', 'end_day']);;
    }
}
