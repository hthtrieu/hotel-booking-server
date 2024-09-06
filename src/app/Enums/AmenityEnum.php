<?php

namespace App\Enums;

use App\Attributes\Label;
use App\Traits\AttributeEnum;

enum AmenityEnum: string
{
    use AttributeEnum;

    #[Label('Hotel Amenity')]
    case HotelAmenity = 'hotel';
    #[Label('Room Amenity')]
    case RoomAmenity = 'room';
}
