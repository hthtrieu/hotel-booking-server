<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoomTypes;

class RoomReserved extends BaseModel
{
    protected $fillable = [
        'start_day',
        'end_day',
    ];
    public function room()
    {
        return $this->belongsTo(RoomTypes::class, 'room_id');
    }
}
