<?php

namespace App\Enums;

use App\Attributes\Label;
use App\Traits\AttributeEnum;

enum HotelStarsEnum: int
{
    use AttributeEnum;
    #[Label('One Star')]
    case OneStar = 1;
    #[Label('Two Stars')]
    case TwoStars = 2;
    #[Label('Three Stars')]
    case ThreeStars = 3;
    #[Label('Four Stars')]
    case FourStars = 4;
    #[Label('FiveS tars')]
    case FiveStars = 5;
}
