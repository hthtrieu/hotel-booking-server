<?php

namespace App\Repositories\RoomType;

use App\Enums\ReservationStatusEnum;
use App\Repositories\BaseRepository;
use App\Models\Reservation;
use App\Models\RoomTypes;
use App\Dtos\Room\RoomAvailableOption;

class RoomTypeRepository extends BaseRepository implements IRoomTypeRepository
{
    protected $modelName = RoomTypes::class;
    public function getRoomAvailableForRoomTypes(RoomAvailableOption $data)
    {
        $query = RoomTypes::with(['rooms'])->where('id', '=', $data->roomTypeId);
        if ($data->roomCount) {
            $query->where('room_count', '>=', $data->roomCount);
        }
        $query->whereHas(
            'rooms',
            function ($q) use ($data) {
                $q->whereDoesntHave('reservations', function ($q) use ($data) {
                    $q->where(function ($q) use ($data) {
                        $q->whereBetween('room_reserveds.start_day', [$data->checkinDay, $data->checkoutDay])
                            ->orWhereBetween('room_reserveds.end_day', [$data->checkinDay, $data->checkoutDay])
                            ->orWhere(function ($q) use ($data) {
                                $q->where('room_reserveds.start_day', '<=', $data->checkinDay)
                                    ->where('room_reserveds.end_day', '>=', $data->checkoutDay);
                            });
                    });
                });
            }
        );
        $rooms = $query->get();
        dd($rooms);
        return $rooms;
    }
}
