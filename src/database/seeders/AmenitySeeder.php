<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;
use Illuminate\Support\Str;
use App\Enums\AmenityEnum;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Amenity::insert([
            // hotel amenity
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Free Wifi'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Car Parking'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'No Smoking'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Family Room'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Restaurant'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Swimming Pool'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Gym'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Spa'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::HotelAmenity->value,
                'name' => 'Bar'
            ],

            // room amenity
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Air Conditioning'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Flat Screen TV'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Private Bathroom'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Mini Bar'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Safe Box'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Coffee Maker'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Hair Dryer'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Room Service'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Desk'
            ],
            [
                'id' => Str::uuid(),
                'type' => AmenityEnum::RoomAmenity->value,
                'name' => 'Wardrobe'
            ]
        ]);
    }
}
