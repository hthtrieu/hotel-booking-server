<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomReserved extends BaseModel
{
    protected $fillable = [
        'start_day',
        'end_day',
    ];
    // public function reservations(): HasMany
    // {
    //     return $this->hasMany(Room::class, 'room_bed_types');
    // }
}
