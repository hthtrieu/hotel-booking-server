<?php

namespace App\Dtos\Reservation;

// use App\Models\Amenity;
// use App\Models\RoomTypes;
// use Illuminate\Database\Eloquent\Collection;

class ReservationDTO
{
    public function __construct(
        public string $id,
        // public string $email,
        public float $site_fees,
        public float $tax_paid,
        public float $total_price,
        public string $status,
        public string $reservation_code,
        public string $checkin,
        public string $checkout,
        public int $night_count,

    ) {}

    public static function fromData($reservation)
    {
        return new self(
            $reservation->id,
            // $reservation->email,
            $reservation->site_fees,
            $reservation->tax_paid,
            $reservation->total_price,
            $reservation->status,
            $reservation->reservation_code ?? "",
            $reservation->checkin,
            $reservation->checkout,
            $reservation->night_count,

        );
    }
}
