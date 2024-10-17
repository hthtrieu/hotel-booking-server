<?php

namespace App\Helpers;

class ReservationHelper
{

    public static function generateReservationCode(): string
    {
        $dateString = DayTimeHelper::getLocalDateTimeFormat();
        return 'RES'. $dateString;
    }
}
