<?php

namespace App\Services\Reservation;

use App\Exceptions\ResponseException;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Models\Reservation;
use App\Dtos\Reservation\CreateReservationRequestDTO;
use App\Dtos\Reservation\ReservationResponseDTO;

interface IReservationService
{
    public function createNewReservation(CreateReservationRequestDTO $request);

    public function getInvoiceByReservationId(string $id): ReservationResponseDTO;

    public function getReservationByCode(string $code);

    public function checkValidReservationAmount(CreateReservationRequestDTO $data);

    public function getReservationStartEndDayById(string $reservationId);

    public function TestSendMail();
}
