<?php

namespace App\Models;

use App\Enums\AmenityEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Amenity extends BaseModel
{
    protected $fillable = [
        'name',
        'type'
    ];
    protected $casts = [
        'type' => AmenityEnum::class,
    ];

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotels::class, 'hotel_amenities', 'amenity_id', 'hotel_id');
    }

    public function room(): belongsToMany
    {
        return $this->belongsToMany(RoomTypes::class, 'room_amenities', 'amenity_id', 'room_id');
    }
}
