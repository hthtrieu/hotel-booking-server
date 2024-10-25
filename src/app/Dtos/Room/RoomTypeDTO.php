<?php

namespace App\Dtos\Room;

use Illuminate\Support\Collection;

class RoomTypeDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public float $price,
        public int $bathroom_count,
        public float $room_area,
        public int $adult_count,
        public int $children_count,
        public int $room_count,
        public string $description,
        public array $images,
        public Collection $amenities,
        public Collection $bedTypes,

        public ?int $total_rooms = null, // Thêm thuộc tính này
        public ?int $days_count = null, // Thêm thuộc tính này

    ) {}

    public static function fromModel($roomType)
    {
        return new self(
            $roomType->id ?? '',
            $roomType->name ?? '',
            $roomType->price ?? 0.0,
            $roomType->bathroom_count ?? 0,
            $roomType->room_area ?? 0.0,
            $roomType->adult_count ?? 0,
            $roomType->children_count ?? 0,
            $roomType->room_count ?? 0,
            $roomType->description ?? '',
            property_exists($roomType, 'images') ? $roomType->images->toArray() : [],
            property_exists($roomType, 'amenities') ? $roomType->amenities : collect(),
            property_exists($roomType, 'bedTypes') ? $roomType->bedTypes : collect(),
            $roomType->total_rooms ?? null, // Sử dụng khi từ model hoặc thêm vào sau
            $roomType->days_count ?? null, // Sử dụng khi từ model hoặc thêm vào sau

        );
    }
}
