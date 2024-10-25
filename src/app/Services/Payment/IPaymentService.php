<?php

namespace App\Services\Payment;

use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Dtos\Reservation\CreateReservationRequestDTO;
use App\Dtos\Payment\CreateRefundRequestDTO;

interface IPaymentService
{
    public function createPaymentRequest(CreateReservationRequestDTO $data, $request);

    public function paymentSuccess($request);

    public function refund(CreateRefundRequestDTO $request);
}
