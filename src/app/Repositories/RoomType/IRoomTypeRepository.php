<?php

namespace App\Repositories\RoomType;

use App\Dtos\Room\RoomAvailableOption;
use App\Repositories\BaseRepositoryInterface;

interface IRoomTypeRepository extends BaseRepositoryInterface
{

    public function getRoomAvailableForRoomTypes(RoomAvailableOption $data);
}
