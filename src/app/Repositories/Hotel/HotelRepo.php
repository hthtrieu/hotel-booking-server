<?php

namespace App\Repositories\Hotel;

use App\Models\Hotels;
use App\Models\Room;
use App\Models\RoomTypes;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class HotelRepo extends BaseRepository implements IHotelRepo
{
    protected $modelName = Hotels::class;

    public function getHotelsList(array $filters)
    {
        $query = Hotels::with(['reviews', 'roomTypes', 'amenities']);

        if (!empty($filters['province'])) {
            $query->where('province', 'LIKE', '%' . $filters['province'] . '%');
        }

        if (!empty($filters['hotel_starts']) && is_int(intval($filters['hotel_starts']))) {
            $query->where('hotel_starts', '=', $filters['hotel_starts']);
        }

        if (!empty($filters['review']) && is_float(floatval($filters['review']))) {
            $query->whereHas('reviews', function ($q) use ($filters) {
                $q->select('hotel_id', DB::raw('AVG(rating) as avg_rating'))
                    ->groupBy('hotel_id')
                    ->havingRaw('AVG(rating) >= ?', [floatval($filters['review'])]);
            });
        }

        // Thêm kiểm tra số lượng phòng chưa được đặt
        if (!empty($filters['rooms']) && is_int(intval($filters['rooms']))) {
            $query->whereHas('roomTypes', function ($q) use ($filters) {
                $q->select('hotel_id', DB::raw('room_types.id as room_type_id'), 'room_types.room_count') // Lấy thêm room_count
                    ->join('rooms', 'room_types.id', '=', 'rooms.room_types_id')
                    ->leftJoin('room_reserveds', function ($join) use ($filters) {
                        $join->on('rooms.id', '=', 'room_reserveds.room_id')
                            ->where(function ($q) use ($filters) {
                                $q->whereBetween('room_reserveds.start_day', [$filters['checkin'], $filters['checkout']])
                                    ->orWhereBetween('room_reserveds.end_day', [$filters['checkin'], $filters['checkout']])
                                    ->orWhere(function ($q) use ($filters) {
                                        $q->where('room_reserveds.start_day', '<=', $filters['checkin'])
                                            ->where('room_reserveds.end_day', '>=', $filters['checkout']);
                                    });
                            });
                    })
                    ->groupBy('room_types.id')
                    ->havingRaw('COUNT(room_reserveds.id) <= room_types.room_count - ?', [intval($filters['rooms'])]); // Kiểm tra số lượng phòng chưa được đặt
            });
        }

        $hotels = $query->get();

        $result = [];
        if ($hotels) {
            foreach ($hotels as $hotel) {
                // Tính điểm trung bình reviews cho mỗi khách sạn
                $averageRating = $hotel->reviews->avg('rating');

                // Lấy danh sách phòng phù hợp với các bộ lọc
                $rooms = $this->getRoomsForHotel($hotel, $filters);

                // Tìm giá phòng nhỏ nhất trong danh sách phòng phù hợp
                $minPrice = $rooms->min('price');
                // dd($rooms[0]->price);
                // Chỉ thêm khách sạn vào kết quả nếu có phòng phù hợp
                if ($rooms->isNotEmpty()) {
                    $result[] = [
                        'hotel' => $hotel,
                        'average_rating' => $averageRating,
                        'min_price' => $minPrice,
                        // 'rooms' => $rooms, // Nếu cần trả về danh sách phòng, bỏ comment dòng này
                    ];
                }
            }
        }

        return $result;
    }

    private function getRoomsForHotel($hotel, array $filters)
    {
        $query = DB::table('rooms')
            ->join('room_types', 'rooms.room_types_id', '=', 'room_types.id')
            ->where('room_types.hotel_id', $hotel->id);

        // Lọc theo số người lớn và trẻ em
        if (!empty($filters['adult'])) {
            $query->where('room_types.adult_count', '>=', $filters['adult']);
        }

        if (!empty($filters['children'])) {
            $query->where('room_types.children_count', '>=', $filters['children']);
        }

        // Lọc theo giá (min_price và max_price)
        if (!empty($filters['min_price']) && is_double(doubleval($filters['min_price']))) {
            $query->where('room_types.price', '>=', doubleval($filters['min_price']));
        }

        if (!empty($filters['max_price']) && is_double(doubleval($filters['max_price']))) {
            $query->where('room_types.price', '<=', doubleval($filters['max_price']));
        }

        // Lọc theo ngày checkin và checkout
        if (!empty($filters['checkin']) && !empty($filters['checkout'])) {
            $query->whereNotExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                    ->from('room_reserveds')
                    ->whereRaw('room_reserveds.room_id = rooms.id')
                    ->where(function ($q) use ($filters) {
                        $q->whereBetween('room_reserveds.start_day', [$filters['checkin'], $filters['checkout']])
                            ->orWhereBetween('room_reserveds.end_day', [$filters['checkin'], $filters['checkout']])
                            ->orWhere(function ($q) use ($filters) {
                                $q->where('room_reserveds.start_day', '<=', $filters['checkin'])
                                    ->where('room_reserveds.end_day', '>=', $filters['checkout']);
                            });
                    });
            });
        }

        // Lấy kết quả
        return $query->select(['rooms.*', 'room_types.*'])->get();
    }


    public function getHotelById(string $id)
    {
        return $this->findBy('id', $id, ['amenities', 'roomTypes', 'reviews']);
    }
}
