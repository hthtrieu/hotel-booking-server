<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "owner_id" => $this->owner_id,
            "description" => $this->description,
            "hotel_star" => $this->hotel_star,
            "phone_number" => $this->phone_number,
            "email" => $this->email,
            "check_in_time" => $this->check_in_time,
            "check_out_time" => $this->check_out_time,
            "province" => $this->province,
            "district" =>  $this->district,
            "ward" => $this->ward,
            "street" => $this->street,
            "status" => $this->status,
            "average_rating" => $this->average_rating,
            "address" => $this->address,
            "min_price" => $this->min_price,
            "images" => ImageResource::collection($this->images),
            "amenities" => AmenityResource::collection($this->amenities),
            'room_types' => RoomTypesResource::collection($this->roomTypes),
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }
}
