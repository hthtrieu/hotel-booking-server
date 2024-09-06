<?php

namespace App\Enums;

use App\Attributes\Label;
use App\Traits\AttributeEnum;

enum HotelStatusEnum: string
{
    use AttributeEnum;

    #[Label('Hotel Active')]
    case HotelActive = 'ACTIVE';
    #[Label('Hotel InActive')]
    case HotelInActive = 'INACTIVE';
    #[Label('Hotel Occupied')]
    case HotelOccupied = 'OCCUPIED';
}
