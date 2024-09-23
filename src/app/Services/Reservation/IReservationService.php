<?php

namespace App\Services\Reservation;

use App\Http\Requests\Reservation\CreateReservationRequest;

interface IReservationService
{
    public function createNewReservation(CreateReservationRequest $request);
}
