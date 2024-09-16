<?php

namespace App\Repositories\Hotel;

use App\Models\Hotels;
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
        $roomQuery = RoomTypes::query()->where('hotel_id', $hotel->id);

        // Lọc theo số người lớn và trẻ em
        if (!empty($filters['adult'])) {
            $roomQuery->where('adult_count', '>=', $filters['adult']);
        }

        if (!empty($filters['children'])) {
            $roomQuery->where('children_count', '>=', $filters['children']);
        }

        // Lọc theo giá phòng (min_price và max_price)
        if (!empty($filters['min_price']) && is_double(doubleval($filters['min_price']))) {
            $roomQuery->where('price', '>=', doubleval($filters['min_price']));
        }
        if (!empty($filters['max_price']) && is_double(doubleval($filters['max_price']))) {
            $roomQuery->where('price', '<=', doubleval($filters['max_price']));
        }

        // Lọc theo ngày checkin và checkout
        if (!empty($filters['checkin']) && !empty($filters['checkout'])) {
            $roomQuery->whereDoesntHave('roomReserveds', function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    $q->whereBetween('start_day', [$filters['checkin'], $filters['checkout']])
                        ->orWhereBetween('end_day', [$filters['checkin'], $filters['checkout']]);
                });
            });
        }

        return $roomQuery->get();
    }

    public function getHotelById(string $id)
    {
        return $this->findBy('id', $id, ['amenities', 'roomTypes', 'reviews']);
    }
}
