<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'vnp_HashSecret' => env('VNP_HASH_SECRET'),
    'vnp_TmnCode' => env("VNP_TMN_CODE"),
    'vnp_URL' => env("VNP_URL"),
    'vnp_Return_URL' => env("VNP_RETURN_URL")
];
