<?php

namespace App\Models;

use App\Enums\AmenityEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends BaseModel
{
    protected $fillable = [
        'name',
        'type'
    ];
    protected $casts = [
        'type' => AmenityEnum::class,
    ];
}
