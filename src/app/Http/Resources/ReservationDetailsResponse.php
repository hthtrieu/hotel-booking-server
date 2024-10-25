<?php

namespace App\Http\Resources;

use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationDetailsResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->roomTypesDTO);
        return [
            "user" => new UserResource($this->userDTO),
            "hotel" => new HotelResource($this->hotelDTO),
            "reservation" => new ReservationResource($this->reservationDTO),
            "invoice" => new InvoiceResource($this->invoiceDTO),
            "room_types" => RoomTypesResource::collection($this->roomTypesDTO),
        ];
    }
}
