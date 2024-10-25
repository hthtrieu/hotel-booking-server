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
        // Bắt đầu query với model Hotels và các mối quan hệ
        $query = Hotels::with(['reviews', 'roomTypes', 'amenities']);

        // Lọc theo tỉnh/thành phố
        if (!empty($filters['province'])) {
            $query->where('province', 'LIKE', '%' . $filters['province'] . '%');
        }

        // Lọc theo số sao của khách sạn
        if (!empty($filters['hotel_star']) && is_int(intval($filters['hotel_star']))) {
            $query->where('hotel_star', '>=', intval($filters['hotel_star']));
        }

        // Lọc theo đánh giá trung bình
        if (!empty($filters['review']) && is_float(floatval($filters['review']))) {
            $query->whereHas('reviews', function ($q) use ($filters) {
                $q->select('hotel_id', DB::raw('AVG(rating) as avg_rating'))
                    ->groupBy('hotel_id')
                    ->havingRaw('AVG(rating) >= ?', [floatval($filters['review'])]);
            });
        }

        // Lọc theo số lượng phòng
        if (!empty($filters['rooms']) && is_int(intval($filters['rooms']))) {
            $query->whereHas('roomTypes', function ($q) use ($filters) {
                $q->select('hotel_id', DB::raw('room_types.id as room_type_id'), 'room_types.room_count')
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
                    ->havingRaw('COUNT(room_reserveds.id) <= room_types.room_count - ?', [intval($filters['rooms'])]);
            });
        }

        // Lọc theo số lượng người lớn
        if (!empty($filters['adult'])) {
            $query->whereHas('roomTypes', function ($q) use ($filters) {
                $q->where('adult_count', '>=', $filters['adult']);
            });
        }

        // Lọc theo số lượng trẻ em
        if (!empty($filters['children'])) {
            $query->whereHas('roomTypes', function ($q) use ($filters) {
                $q->where('children_count', '>=', $filters['children']);
            });
        }

        // Lọc theo giá (min_price và max_price)
        if (!empty($filters['min_price']) && is_double(doubleval($filters['min_price']))) {
            $query->whereHas('roomTypes', function ($q) use ($filters) {
                $q->where('price', '>=', doubleval($filters['min_price']));
            });
        }

        if (!empty($filters['max_price']) && is_double(doubleval($filters['max_price']))) {
            $query->whereHas('roomTypes', function ($q) use ($filters) {
                $q->where('price', '<=', doubleval($filters['max_price']));
            });
        }

        // Phân trang
        $perPage = $filters['page_size'] ?? 6; // Số lượng kết quả mỗi trang (mặc định là 10)
        $pageIndex  = $filters['page_index'] ?? 1;

        // Sử dụng paginate để phân trang
        $hotels = $query->paginate($perPage, ['*'], 'page', $pageIndex);

        // Trả về kết quả phân trang cùng với các tham số filters
        return $hotels->appends($filters);
    }

    public function getHotelById(string $id)
    {
        return $this->findBy('id', $id, ['amenities', 'roomTypes.amenities', 'roomTypes.rooms', 'reviews', 'roomTypes.bedTypes']);
    }
}
