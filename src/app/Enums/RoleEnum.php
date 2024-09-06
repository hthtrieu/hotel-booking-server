<?php

namespace App\Enums;

use App\Attributes\Label;
use App\Traits\AttributeEnum;

enum RoleEnum: int
{
    use AttributeEnum;

    #[Label('Admin')]
    case ADMINISTRATOR = 0;
    #[Label('HOST')]
    case HOST = 1;
    #[Label('User')]
    case USER = 10;
}
