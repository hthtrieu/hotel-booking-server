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
        // Lấy ID của host
        $hostId = User::where('email', 'host@host.vn')->first()->id;

        // Lấy tất cả người dùng có role USER
        $users = User::where('role', RoleEnum::USER->value)->get();

        // Tạo các khách sạn
        $hotels = [
            [
                'id' => Str::uuid(),
                'name' => 'Menora Premium Da Nang - Sea Corner Boutique',
                'description' => 'Quay mặt ra bãi biển ở thành phố Đà Nẵng, Menora Premium Da Nang - Sea Corner Boutique cung cấp chỗ nghỉ 3 sao và có hồ bơi ngoài trời, sân hiên cũng như nhà hàng.',
                'province' => 'Thành phố Đà Nẵng',
                'district' => 'Ngũ Hành Sơn',
                'ward' => 'Mỹ An',
                'street' => '196 Trần Bạch Đằng',
                'phone_number' => '123456789',
                'email' => 'ks1@gmail.com',
                'check_in_time' => '07:00:00',
                'check_out_time' => '23:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_starts' => HotelStarsEnum::FourStars->value,
                'owner_id' => $hostId,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'MANGO MANGO HOTEL & APARTMENT',
                'description' => 'MANGO MANGO HOTEL & APARTMENT has city views, free WiFi and free private parking, set in Da Nang, 400 metres from My Khe Beach.',
                'province' => 'Thành phố Đà Nẵng',
                'district' => 'Ngũ Hành Sơn',
                'ward' => 'Mỹ An',
                'street' => 'Mỹ Đa Đông 12',
                'phone_number' => '123456789',
                'email' => 'ks2@gmail.com',
                'check_in_time' => '07:00:00',
                'check_out_time' => '23:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_starts' => HotelStarsEnum::FourStars->value,
                'owner_id' => $hostId,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Merry Land Hotel Da Nang',
                'description' => 'Merry Land Hotel Da Nang cung cấp các phòng nghỉ tại thành phố Đà Nẵng, cách Cầu Sông Hàn chỉ 2 phút lái xe.',
                'province' => 'Thành phố Đà Nẵng',
                'district' => 'Sơn Trà',
                'ward' => 'An Hải Bắc',
                'street' => '21 Phạm Văn Đồng',
                'phone_number' => '123456789',
                'email' => 'ks3@gmail.com',
                'check_in_time' => '07:00:00',
                'check_out_time' => '23:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_starts' => HotelStarsEnum::ThreeStars->value,
                'owner_id' => $hostId,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Shara Hotel Da Nang',
                'description' => 'Tọa lạc tại thành phố Đà Nẵng, cách Bãi biển Mỹ Khê 600 m và Bãi biển Bắc Mỹ An 1,3 km.',
                'province' => 'Thành phố Đà Nẵng',
                'district' => 'Ngũ Hành Sơn',
                'ward' => 'Bắc Mỹ Phú',
                'street' => '53 Phan Liem',
                'phone_number' => '123456789',
                'email' => 'ks4@gmail.com',
                'check_in_time' => '07:00:00',
                'check_out_time' => '23:00:00',
                'status' => HotelStatusEnum::HotelActive->value,
                'hotel_starts' => HotelStarsEnum::FiveStars->value,
                'owner_id' => $hostId,
            ],
        ];

        // Dùng create để tạo các khách sạn và lưu trữ đối tượng
        foreach ($hotels as $hotelData) {
            $hotel = Hotels::create($hotelData);

            // Tạo đánh giá cho mỗi khách sạn
            foreach ($users as $user) {
                Reviews::insert([
                    [
                        'id' => Str::uuid(),
                        'rating' => rand(1, 10),
                        'comment' => 'Good Service',
                        'user_id' => $user->id,
                        'hotel_id' => $hotel->id,
                    ],
                    [
                        'id' => Str::uuid(),
                        'rating' => rand(1, 10),
                        'comment' => 'Nice',
                        'user_id' => $user->id,
                        'hotel_id' => $hotel->id,
                    ],
                    [
                        'id' => Str::uuid(),
                        'rating' => rand(1, 10),
                        'comment' => 'Ok',
                        'user_id' => $user->id,
                        'hotel_id' => $hotel->id,
                    ],
                ]);
            }
        }
    }
}
