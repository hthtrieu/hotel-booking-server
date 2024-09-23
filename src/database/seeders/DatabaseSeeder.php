<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AmenitySeeder::class,
            ViewsSeeder::class,
            BedTypesSeeder::class,
            HotelsSeeder::class,
            HotelAmenitySeeder::class,
            RoomTypesSeeder::class,
            RoomAmenitySeeder::class,
            RoomSeeder::class,
        ]);
    }
}
