<?php

namespace App\Repositories\RoomType;

use App\Dtos\Room\RoomAvailableOption;

interface IRoomTypeRepository
{

    public function getRoomAvailableForRoomTypes(RoomAvailableOption $data);
}
