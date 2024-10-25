<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ReservationDetailsResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReservationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->resource);
        return [
            "reservations" => ReservationDetailsResponse::collection($this->resource),
        ];
    }
}
