<?php

namespace App\Models;

class BedTypes extends BaseModel
{
    protected $fillable = [
        'name',
        'description'
    ];
    public function roomTypes()
    {
        return $this->belongsToMany(RoomTypes::class, 'room_bed_types');
    }
}
