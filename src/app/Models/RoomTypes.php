<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomTypes extends BaseModel
{
    protected $fillable = [
        'price',
        'bathroom_count',
        'room_area',
        'adult_count',
        'children_count',
        'description',
        'room_count',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotels::class, 'hotel_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'room_types_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities', 'room_id', 'amenity_id');
    }

    public function bedTypes(): BelongsToMany
    {
        return $this->belongsToMany(BedTypes::class, 'room_bed_types');
    }
}
