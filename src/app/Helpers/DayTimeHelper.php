<?php

namespace App\Helpers;

use Carbon\Carbon;

class DayTimeHelper
{

    public static function convertStringToDate(string $stringDate) {}

    public static function convertStringToLocalDateTime(string $datetimeString)
    {
        return Carbon::createFromFormat('YmdHis', $datetimeString);
    }

    public static function getLocalDateTimeFormat(string $format = 'YmdHis')
    {
        return Carbon::now('Asia/Ho_Chi_Minh')->format($format);
    }

    public static function setExpireTimeForPayment()
    {
        return Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->addMinutes(10)->format('YmdHis');
    }
}
