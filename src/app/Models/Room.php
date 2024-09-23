<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends BaseModel
{
    use HasFactory;
    public function roomTypes(): BelongsTo
    {
        return $this->belongsTo(RoomTypes::class, 'room_types_id');
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'room_reserveds', 'room_id', 'reservation_id')
            ->withPivot(['start_day', 'end_day']);
    }
}
