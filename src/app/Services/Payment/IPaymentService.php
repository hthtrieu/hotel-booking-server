<?php

namespace App\Services\Payment;

use App\Http\Requests\Reservation\CreateReservationRequest;

interface IPaymentService
{
    public function createPaymentRequest(CreateReservationRequest $request);

    public function paymentSuccess($request);

    public function refund($request);
}
