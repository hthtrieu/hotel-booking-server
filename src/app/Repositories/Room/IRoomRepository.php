<?php

namespace App\Repositories\Room;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Hotels;

interface IRoomRepository extends BaseRepositoryInterface
{

    public function getHotelByRoomId(string $roomId): Hotels;
}
