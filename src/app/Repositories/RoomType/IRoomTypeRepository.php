<?php

namespace App\Repositories\RoomType;

use App\Dtos\Room\RoomAvailableOption;
use App\Repositories\BaseRepositoryInterface;

interface IRoomTypeRepository extends BaseRepositoryInterface
{

    //! do not use this function
    public function getRoomAvailableForRoomTypes(RoomAvailableOption $data);

    public function getRoomAmenities(string $id);
}
