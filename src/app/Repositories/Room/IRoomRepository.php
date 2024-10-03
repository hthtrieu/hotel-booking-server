<?php

namespace App\Repositories\Room;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Hotels;
use App\Models\Room;

interface IRoomRepository extends BaseRepositoryInterface
{
    public function getHotelByRoomId(string $roomId): Hotels;
    public function getRoomAvaiable(string $roomTypeId, $startDay, $endDay, $roomCount);
}
