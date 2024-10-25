<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            "site_fees" => $this->site_fees,
            "tax_paid" => $this->tax_paid,
            "total_price" => $this->total_price,
            "status" => $this->status,
            "reservation_code" => $this->reservation_code ? $this->reservation_code : "",
            "checkin" => $this->checkin,
            "checkout" => $this->checkout,
            "night_count" => $this->night_count,
        ];
    }
}
