<?php

namespace App\Repositories\Reservation;

use App\Enums\ReservationStatusEnum;
use App\Helpers\DayTimeHelper;
use App\Repositories\BaseRepository;
use App\Models\Reservation;
use Illuminate\Support\Str;

class ReservationRepository extends BaseRepository implements IReservationRepository
{
    protected $modelName = Reservation::class;

    public function findWithLock($id)
    {
        return Reservation::where('id', $id)->lockForUpdate()->first();
    }
    public function createNewReservation($data)
    {
        $newReservation = new Reservation();
        $newReservation->email = $data['email'];
        $newReservation->site_fees = ($data['total_price'] * 20 / 100);
        $newReservation->tax_paid = $data['tax_paid'];
        $newReservation->status = ReservationStatusEnum::PENDING->value;
        $newReservation->total_price = $data['total_price'];
        $newReservation->expire_time = DayTimeHelper::setExpireTimeForPayment();
        $newReservation->user()->associate($data['user']);

        // Lưu reservation trước
        $newReservation->save();


        $rooms = $data['rooms'];
        foreach ($rooms as $room) {
            $newReservation->rooms()->attach($room['id'], [
                'start_day' => $data['start_day'],
                'end_day' => $data['end_day'],
                'id' => Str::uuid(),
            ]);
        }

        return $newReservation;
    }
}
