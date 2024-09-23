<?php

namespace App\Repositories\Reservation;

use App\Models\Reservation;

interface IReservationRepository
{

    public function createNewReservation($data);
}
