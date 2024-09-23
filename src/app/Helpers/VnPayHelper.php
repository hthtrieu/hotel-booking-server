<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class VnPayHelper
{

    public static function generateOrderId(): string
    {
        $timeRequest = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->format('YmdHis');
        $orderId = $timeRequest + random_int(1, 1000);
        return $orderId;
    }

    public static function getIpRequest(Request $request)
    {
        return $request->ip();
    }
}
