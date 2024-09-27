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
    #[Label('User Not Register')]
    case USER_NOT_REGISTER = 11;
}
