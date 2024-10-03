<?php

namespace App\Repositories\RoomType;

use App\Enums\ReservationStatusEnum;
use App\Repositories\BaseRepository;
use App\Models\Reservation;
use App\Models\RoomTypes;
use App\Dtos\Room\RoomAvailableOption;
use App\Helpers\DayTimeHelper;

class RoomTypeRepository extends BaseRepository implements IRoomTypeRepository
{
    protected $modelName = RoomTypes::class;

    public function getRoomAvailableForRoomTypes(RoomAvailableOption $data)
    {
        return RoomTypes::with(['rooms' => function ($query) use ($data) {
            $query->whereHave('reservations', function ($query) use ($data) {
                $query->where(function ($query) use ($data) {
                    //!todo: check if reservations's status is not confirm when expired
                    $query->whereBetween('room_reserveds.start_day', [$data->checkinDay, $data->checkoutDay])
                        ->orWhereBetween('room_reserveds.end_day', [$data->checkinDay, $data->checkoutDay])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('room_reserveds.start_day', '<=', $data->checkinDay)
                                ->where('room_reserveds.end_day', '>=', $data->checkoutDay);
                        });
                });
            });
        }])
            ->where('id', '=', $data->roomTypeId)
            ->when($data->roomCount, function ($query) use ($data) {
                return $query->where('room_count', '>=', $data->roomCount);
            })
            ->get();
    }
}
