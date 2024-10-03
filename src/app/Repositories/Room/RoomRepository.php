<?php

namespace App\Repositories\Room;

use App\Enums\ReservationStatusEnum;
use App\Helpers\DayTimeHelper;
use App\Models\Hotels;
use App\Models\Room;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

class RoomRepository extends BaseRepository implements IRoomRepository
{
    protected $modelName = Room::class;

    public function getHotelByRoomId(string $roomId): Hotels
    {
        return new Hotels();
    }

    public function getRoomAvaiable(string $roomTypeId, $startDay, $endDay, $roomCount)
    {
        $startDay = Carbon::parse($startDay);
        $endDay = Carbon::parse($endDay);
        $currentTime = DayTimeHelper::getLocalDateTimeFormat();
         // Truy vấn để tìm các phòng chưa được đặt hoặc không có đơn đặt phòng hợp lệ trong khoảng thời gian startDay và endDay
        $availableRooms = Room::where('room_types_id', $roomTypeId)
            ->whereDoesntHave('reservations', function ($query) use ($startDay, $endDay, $currentTime) {
                $query->where(function ($q) use ($startDay, $endDay) {
                    // Điều kiện kiểm tra thời gian đặt phòng nằm trong khoảng startDay đến endDay
                    $q->whereBetween('room_reserveds.start_day', [$startDay, $endDay])
                        ->orWhereBetween('room_reserveds.end_day', [$startDay, $endDay])
                        ->orWhere(function ($query) use ($startDay, $endDay) {
                            $query->where('room_reserveds.start_day', '<=', $startDay)
                                ->where('room_reserveds.end_day', '>=', $endDay);
                        });
                })
                    // Nếu expire_time còn hiệu lực, chỉ kiểm tra các đơn chưa thanh toán hoặc xác nhận
                    ->where(function ($query) use ($currentTime) {
                        $query->where('expire_time', '>=', $currentTime) // Đơn còn hiệu lực
                            ->where('status', '!=', ReservationStatusEnum::CONFIRMED); // Đơn chưa thanh toán hoặc xác nhận
                    });
            })
            ->take($roomCount) // Giới hạn theo số lượng phòng yêu cầu
            ->get();
        return $availableRooms;
    }
}
