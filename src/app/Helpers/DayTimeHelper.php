<?php

namespace App\Helpers;

use Carbon\Carbon;

class DayTimeHelper
{

    public static function convertDateToString($date, string $format = 'Y-m-d',)
    {
        try {
            $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
            return $carbonDate->format($format);
        } catch (\Exception $e) {
            // Ghi log hoặc xử lý ngoại lệ tùy thuộc vào nhu cầu của bạn
            return null;
        }
    }

    //"2024-10-16 00:55:50" -> 20241016005550
    public static function convertStringToDateTime(string $datetimeString, string $format = 'YmdHis')
    {
        try {
            $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $datetimeString);
            return $dateTime->format($format);
        } catch (\Exception $e) {
            // Ghi log hoặc xử lý ngoại lệ tùy thuộc vào nhu cầu của bạn
            return null;
        }
    }

    //20241019133420 ->2024-10-16 00:55:50
    // Convert "20241016005550" -> "2024-10-16 00:55:50"
    public static function formatStringDateTime(string $dateString, string $inputFormat = 'YmdHis', string $outputFormat = 'Y-m-d H:i:s'): ?string
    {
        try {
            $dateTime = Carbon::createFromFormat($inputFormat, $dateString);
            return $dateTime->format($outputFormat);
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            return null;
        }
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
