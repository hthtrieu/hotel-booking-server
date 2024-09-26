<?php

namespace App\Repositories\Reservation;

use App\Models\Reservation;
use App\Repositories\BaseRepositoryInterface;

interface IReservationRepository extends BaseRepositoryInterface
{

    public function createNewReservation($data);
}
