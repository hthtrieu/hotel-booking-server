<?php

namespace App\Models;

use App\Enums\HotelStarsEnum;
use App\Enums\HotelStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotels extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'hotel_starts',
        'phone_number',
        'email',
        'check_in_time',
        'check_out_time',
        'province',
        'district',
        'ward',
        'street',
        'status'
    ];
    protected $casts = [
        'hotel_starts' => HotelStarsEnum::class,
        'status' => HotelStatusEnum::class,
    ];

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities', 'hotel_id', 'amenity_id');
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomTypes::class, 'hotel_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Reviews::class, 'hotel_id');
    }
}
