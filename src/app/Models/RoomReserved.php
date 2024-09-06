<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReserved extends BaseModel
{
    protected $fillable = [
        'start_day',
        'end_day',
    ];
}
