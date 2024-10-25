<?php

namespace App\Dtos\Payment;

class CreatePaymentUrlDTO
{
    public float $amount;
    public string $requestIp;

    public function __construct(float $amount, string $requestIp)
    {
        $this->amount = $amount;
        $this->requestIp = $requestIp;
    }
}
