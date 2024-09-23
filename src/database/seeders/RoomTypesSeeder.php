<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomTypes;
use App\Models\Hotels;
use App\Models\BedTypes;
use Illuminate\Support\Str;

class RoomTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = Hotels::all();
        $bedTypes = BedTypes::all(); // Lấy tất cả các loại giường

        foreach ($hotels as $hotel) {
            $roomType1 = RoomTypes::create([
                'id' => Str::uuid(),
                'name' => 'Single ROOM',
                'price' => 500000.0,
                'bathroom_count' => 1,
                'room_area' => 5.5,
                'adult_count' => 2,
                'children_count' => 1,
                'description' => 'description',
                'hotel_id' => $hotel->id,
                'room_count' => 3,
            ]);

            $roomType2 = RoomTypes::create([
                'id' => Str::uuid(),
                'name' => 'Queen ROOM',
                'price' => 1000000.0,
                'bathroom_count' => 1,
                'room_area' => 10,
                'adult_count' => 2,
                'children_count' => 1,
                'description' => 'description',
                'hotel_id' => $hotel->id,
                'room_count' => 2,

            ]);

            $roomType3 = RoomTypes::create([
                'id' => Str::uuid(),
                'name' => 'Deluxe ROOM',
                'price' => 2000000.0,
                'bathroom_count' => 1,
                'room_area' => 8,
                'adult_count' => 2,
                'children_count' => 1,
                'description' => 'description',
                'hotel_id' => $hotel->id,
                'room_count' => 1,

            ]);
            foreach ([$roomType1, $roomType2, $roomType3] as $roomType) {
                foreach ($bedTypes as $bedType) {
                    $roomType->bedTypes()->attach($bedType->id, [
                        'id' => Str::uuid(),
                    ]);
                }
            }
        };
    }
}
