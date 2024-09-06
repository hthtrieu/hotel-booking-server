<?php

namespace Database\Seeders;

use App\Enums\AmenityEnum;
use Illuminate\Database\Seeder;
use App\Models\Hotels;
use App\Models\Amenity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HotelAmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả amenities với type là RoomAmenity
        $hotelAmenities = Amenity::where('type', AmenityEnum::HotelAmenity->value)->get();

        // Kiểm tra xem danh sách amenities có ít nhất 3 phần tử không
        if ($hotelAmenities->count() < 3) {
            throw new \Exception('Not enough amenities found for seeding.');
        }

        // Lấy tất cả các loại phòng
        $hotels = Hotels::all();

        // Chèn dữ liệu vào bảng room_amenities
        foreach ($hotels as $hotel) {
            DB::table('hotel_amenities')->insert([
                [
                    'id' => Str::uuid(),
                    'hotel_id' => $hotel->id,
                    'amentiy_id' => $hotelAmenities[0]->id
                ],
                [
                    'id' => Str::uuid(),
                    'hotel_id' => $hotel->id,
                    'amentiy_id' => $hotelAmenities[1]->id
                ],
                [
                    'id' => Str::uuid(),
                    'hotel_id' => $hotel->id,
                    'amentiy_id' => $hotelAmenities[2]->id
                ],
            ]);
        }
    }
}
