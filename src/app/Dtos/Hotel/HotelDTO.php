<?php

namespace App\Dtos\Hotel;

use App\Models\Amenity;
use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Collection;

class HotelDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $owner_id,
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
        public Collection $amenities,
        public Collection $roomTypes,
        public Collection $reviews,
    ) {}

    public static function fromModel($hotel)
    {
        return new self(
            $hotel->id,
            $hotel->name,
            $hotel->owner_id,
            $hotel->description,
            $hotel->hotel_star->value,
            $hotel->phone_number,
            $hotel->email,
            $hotel->check_in_time,
            $hotel->check_out_time,
            $hotel->province,
            $hotel->district,
            $hotel->ward,
            $hotel->street,
            $hotel->status->value,
            $hotel->average_rating,
            $hotel->address,
            $hotel->min_price,
            $hotel->images->toArray(),
            $hotel->amenities,
            $hotel->roomTypes,
            $hotel->reviews ?? collect(),

        );
    }
}
