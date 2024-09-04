<?php

namespace App\Enums;

use App\Attributes\Label;
use App\Traits\AttributeEnum;

enum RoleEnum: int
{
    use AttributeEnum;

    #[Label('Admin')]
    case ADMINISTRATOR = 0;
    #[Label('HR')]
    case HR = 1;
    #[Label('Dev')]
    case DEV = 2;
    #[Label('User')]
    case USER = 10;
}
