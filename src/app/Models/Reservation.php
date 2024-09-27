<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends BaseModel
{
    protected $fillable = [
        'email',
        'site_fees',
        'tax_paid',
        'status',
        'total_price',
        'reservation_code',
        'expire_time',
    ];
    protected $casts = [
        'status' => ReservationStatusEnum::class,
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_reserveds', 'reservation_id', 'room_id')
            ->withPivot(['start_day', 'end_day']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'reservation_id');
    }
}
