<?php

namespace App\Services\Payment;

use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Reservation\CreateReservationRequest;

interface IPaymentService
{
    public function createPaymentRequest(CreateReservationRequest $request);
}