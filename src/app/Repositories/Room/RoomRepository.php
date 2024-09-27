<?php

namespace App\Repositories\Room;

use App\Models\Hotels;
use App\Models\Room;
use App\Repositories\BaseRepository;

class RoomRepository extends BaseRepository implements IRoomRepository
{

    protected $modelName = Room::class;

    public function getHotelByRoomId(string $roomId): Hotels
    {

        return new Hotels();
    }
}
