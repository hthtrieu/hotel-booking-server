<?php

namespace App\DTOs;

class HotelDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public int $owner_id,
        public string $description,
        public int $hotel_star,
        public string $phone_number,
        public string $email,
        public string $check_in_time,
        public string $check_out_time,
        public string $province,
        public string $district,
        public string $ward,
        public string $street,
        public string $status,
        public float $average_rating,
        public string $address,
        public float $min_price,
        public array $images,
        public array $amenities,
        public array $room_types
    ) {}

    public static function fromModel($hotel)
    {
        return new self(
            $hotel->id,
            $hotel->name,
            $hotel->owner_id,
            $hotel->description,
            $hotel->hotel_star,
            $hotel->phone_number,
            $hotel->email,
            $hotel->check_in_time,
            $hotel->check_out_time,
            $hotel->province,
            $hotel->district,
            $hotel->ward,
            $hotel->street,
            $hotel->status,
            $hotel->average_rating,
            $hotel->address,
            $hotel->min_price,
            $hotel->images->toArray(),
            $hotel->amenities->toArray(),
            $hotel->roomTypes->toArray()
        );
    }
}
