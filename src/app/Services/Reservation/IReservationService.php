<?php

namespace App\Services\Reservation;

use App\Exceptions\ResponseException;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Models\Reservation;

interface IReservationService
{
    public function createNewReservation(CreateReservationRequest $request);

    public function getReservationByCode(string $code);

    public function getReservationDetails(string $reservationId = '', Reservation $reservation = null);
}
