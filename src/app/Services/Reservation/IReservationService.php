<?php

namespace App\Services\Reservation;

use App\Exceptions\ResponseException;
use App\Http\Requests\Reservation\CreateReservationRequest;

interface IReservationService
{
    public function createNewReservation(CreateReservationRequest $request);
}
