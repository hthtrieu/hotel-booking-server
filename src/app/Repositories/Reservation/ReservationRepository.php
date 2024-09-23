<?php

namespace App\Repositories\Reservation;

use App\Enums\ReservationStatusEnum;
use App\Repositories\BaseRepository;
use App\Models\Reservation;

class ReservationRepository extends BaseRepository implements IReservationRepository
{
    protected $modelName = Reservation::class;

    public function createNewReservation($data)
    {
        Reservation::created([
            'email' => $data['email'],
            'site_frees' => $data['site_frees'],
            'tax_paid' => $data['tax_paid'],
            'status' => ReservationStatusEnum::PENDING->value,
            'total_price' => $data['total_price'],
            'user_id' => $data['user']->id,
        ]);
    }
}
