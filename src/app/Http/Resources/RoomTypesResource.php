<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypesResource extends JsonResource
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
            "price" => $this->price,
            "bathroom_count" => $this->bathroom_count,
            "room_area" => $this->room_area,
            "adult_count" => $this->adult_count,
            "children_count" => $this->children_count,
            "room_count" => $this->room_count,
            "description" => $this->description,
            "hotel_id" => $this->hotel_id ?? "",
            "images" => ImageResource::collection($this->images) ?? collect(),
            "amenities" => AmenityResource::collection($this->amenities) ?? collect(),
            'bed_types' => BedTypesResource::collection($this->bedTypes) ??  collect(),
            'days_count' => $this->days_count ?? null,
            'total_rooms_order' => $this->total_rooms ?? null,
        ];
    }
}
