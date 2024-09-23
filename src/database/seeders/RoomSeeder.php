<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomTypes;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomTypes::all();
        foreach ($roomTypes as $roomType) {
            for ($i = 1; $i <= $roomType->room_count; $i++) {
                Room::insert([
                    'id' => Str::uuid(),
                    'room_types_id' => $roomType->id,
                ]);
            }
        }
    }
}
