<?php

namespace App\Dtos\Reservation;

use App\Dtos\Hotel\HotelDTO;
use App\Dtos\Invoice\InvoiceDTO;
use App\Dtos\User\UserDTO;
use Illuminate\Support\Collection;

class ReservationResponseDTO
{
    public function __construct(
        public UserDTO $userDTO,
        public HotelDTO $hotelDTO,
        public InvoiceDTO $invoiceDTO,
        public ReservationDTO $reservationDTO,
        public array $roomTypesDTO,
    ) {}

    public static function fromModel($data)
    {
        // dd($data->roomTypesDTO);
        return new self(
            $data->userDTO,
            $data->hotelDTO,
            $data->invoiceDTO,
            $data->reservationDTO,
            property_exists($data, 'roomTypesDTO') ? $data->roomTypesDTO : [],
        );
    }
}
