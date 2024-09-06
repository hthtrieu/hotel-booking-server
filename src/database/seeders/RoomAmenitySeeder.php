<?php

namespace Database\Seeders;

use App\Enums\AmenityEnum;
use Illuminate\Database\Seeder;
use App\Models\RoomTypes;
use App\Models\Amenity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoomAmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả amenities với type là RoomAmenity
        $roomAmenities = Amenity::where('type', AmenityEnum::RoomAmenity->value)->get();

        // Kiểm tra xem danh sách amenities có ít nhất 3 phần tử không
        if ($roomAmenities->count() < 3) {
            throw new \Exception('Not enough amenities found for seeding.');
        }

        // Lấy tất cả các loại phòng
        $roomTypes = RoomTypes::all();

        // Chèn dữ liệu vào bảng room_amenities
        foreach ($roomTypes as $room) {
            DB::table('room_amenities')->insert([
                [
                    'id' => Str::uuid(),
                    'room_id' => $room->id,
                    'amenity_id' => $roomAmenities[0]->id
                ],
                [
                    'id' => Str::uuid(),
                    'room_id' => $room->id,
                    'amenity_id' => $roomAmenities[1]->id
                ],
                [
                    'id' => Str::uuid(),
                    'room_id' => $room->id,
                    'amenity_id' => $roomAmenities[2]->id
                ],
            ]);
        }
    }
}
