<?php

namespace App\Enums;

use App\Attributes\Label;
use App\Traits\AttributeEnum;

enum ReservationStatusEnum: string
{
    use AttributeEnum;

    case CANCELLED = 'CANCELLED';
    case CONFIRMED = 'CONFIRMED';
    case PENDING = 'PENDING';
}
