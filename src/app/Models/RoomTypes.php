<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomTypes extends BaseModel
{
    protected $fillable = [
        'price',
        'bathroom_count',
        'room_area',
        'adult_count',
        'children_count',
        'description',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotels::class, 'hotel_id');
    }

    public function roomReserveds()
    {
        return $this->hasMany(RoomReserved::class, 'room_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function bedTypes(): BelongsToMany
    {
        return $this->belongsToMany(BedTypes::class, 'room_bed_types');
    }
}
