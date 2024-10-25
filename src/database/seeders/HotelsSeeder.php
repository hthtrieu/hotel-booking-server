<?php

namespace Database\Seeders;

use App\Enums\HotelStatusEnum;
use App\Enums\HotelStarsEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use App\Models\Hotels;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Support\Str;

class HotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the ID of the host
        $hostId = User::where('email', 'host@host.vn')->first()->id;

        // Retrieve all users with the USER role
        $users = User::where('role', RoleEnum::USER->value)->get();

        // Create hotels
        $hotels = [
            [
                'id' => Str::uuid(),
                'name' => 'Menora Premium Da Nang - Sea Corner Boutique',
                'description' => 'Facing the beach in Da Nang city, Menora Premium Da Nang - Sea Corner Boutique offers 3-star accommodations and features an outdoor pool, terrace, and restaurant.',
                'province' => 'Da Nang City',
                'district' => 'Ngu Hanh Son',
                'ward' => 'My An',
                'street' => '196 Tran Bach Dang',
                'phone_number' => '0901234567',
                'email' => 'ks1@gmail.com',
                'check_in_time' => '14:00:00',
                'check_out_time' => '12:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_star' => HotelStarsEnum::FourStars->value,
                'owner_id' => $hostId,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sun River Hotel',
                'description' => 'Located in the city center of Da Nang with a stunning view of the Han River and the sea.',
                'province' => 'Da Nang City',
                'district' => 'Hai Chau',
                'ward' => 'Hai Chau 1',
                'street' => '132 Bach Dang',
                'phone_number' => '0902345678',
                'email' => 'ks13@gmail.com',
                'check_in_time' => '13:00:00',
                'check_out_time' => '12:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_star' => HotelStarsEnum::ThreeStars->value,
                'owner_id' => $hostId,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Central Hotel & Spa',
                'description' => 'A small and charming hotel located in the central area of Da Nang city.',
                'province' => 'Da Nang City',
                'district' => 'Hai Chau',
                'ward' => 'Thach Thang',
                'street' => '30-34 Do Quang',
                'phone_number' => '0903456789',
                'email' => 'ks14@gmail.com',
                'check_in_time' => '15:00:00',
                'check_out_time' => '11:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_star' => HotelStarsEnum::FourStars->value,
                'owner_id' => $hostId,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Mandila Beach Hotel',
                'description' => 'A luxurious hotel with a stunning sea view, near My Khe Beach.',
                'province' => 'Da Nang City',
                'district' => 'Son Tra',
                'ward' => 'Phuoc My',
                'street' => '218 Vo Nguyen Giap',
                'phone_number' => '0904567890',
                'email' => 'ks15@gmail.com',
                'check_in_time' => '14:00:00',
                'check_out_time' => '12:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_star' => HotelStarsEnum::FiveStars->value,
                'owner_id' => $hostId,
            ],
            // Add more hotels here
            [
                'id' => Str::uuid(),
                'name' => 'Vinpearl Condotel Riverfront Da Nang',
                'description' => 'One of the famous hotels with a view of the Han River and the city center.',
                'province' => 'Da Nang City',
                'district' => 'Hai Chau',
                'ward' => 'Hai Chau 2',
                'street' => '341 Tran Hung Dao',
                'phone_number' => '0905678901',
                'email' => 'ks16@gmail.com',
                'check_in_time' => '14:00:00',
                'check_out_time' => '12:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_star' => HotelStarsEnum::FiveStars->value,
                'owner_id' => $hostId,
            ],
        ];

        // Use create to generate hotels and store objects
        foreach ($hotels as $hotelData) {
            $hotel = Hotels::create($hotelData);

            // Generate reviews for each hotel
            foreach ($users as $user) {
                Reviews::insert([
                    [
                        'id' => Str::uuid(),
                        'rating' => rand(6, 10),
                        'comment' => 'Excellent service!',
                        'user_id' => $user->id,
                        'hotel_id' => $hotel->id,
                    ],
                    [
                        'id' => Str::uuid(),
                        'rating' => rand(4, 7),
                        'comment' => 'Good, but room for improvement.',
                        'user_id' => $user->id,
                        'hotel_id' => $hotel->id,
                    ],
                ]);
            }
        }
    }
}
