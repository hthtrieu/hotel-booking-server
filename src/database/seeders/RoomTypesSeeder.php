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
        $bedTypes = BedTypes::all(); // Get all bed types
        $roomNames = ['Single ROOM', 'Queen ROOM', 'Deluxe ROOM', 'Family Suite', 'Penthouse', 'Twin Room'];

        foreach ($hotels as $hotel) {
            // Random number of room types for each hotel (between 1 and 4)
            $roomCount = rand(1, 4);

            for ($i = 0; $i < $roomCount; $i++) {
                // Generate a random price rounded to the nearest hundred
                $price = round(rand(500000, 5000000) / 100) * 100;

                $roomType = RoomTypes::create([
                    'id' => Str::uuid(),
                    'name' => $roomNames[array_rand($roomNames)], // Select a random room name
                    'price' => $price, // Price is now rounded to the nearest hundred
                    'bathroom_count' => rand(1, 3), // Random number of bathrooms
                    'room_area' => rand(5, 20), // Random room area
                    'adult_count' => rand(1, 4), // Random number of adults
                    'children_count' => rand(0, 3), // Random number of children
                    'description' => 'description',
                    'hotel_id' => $hotel->id,
                    'room_count' => rand(1, 5), // Random number of rooms
                ]);

                // Attach bed types
                foreach ($bedTypes as $bedType) {
                    $roomType->bedTypes()->attach($bedType->id, [
                        'id' => Str::uuid(),
                    ]);
                }
            }
        }
    }
}
