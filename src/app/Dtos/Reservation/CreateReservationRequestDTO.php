<?php

namespace App\Dtos\Reservation;

class RoomTypeReservedDTO
{
    public $id;
    public $count;
    public $price;

    public function __construct(string $id, int $count, float $price)
    {
        $this->id = $id;
        $this->count = $count;
        $this->price = $price;
    }
}

class CreateReservationRequestDTO
{
    public $hotelId;
    public $note;
    public $name;
    public $email;
    public $phoneNumber;
    public $paymentMethod;
    public $roomTypeReservedList = [];
    public $totalPrice;
    public $tax;
    public $vat;
    public $checkInDay;
    public $checkOutDay;

    public function __construct(
        string $hotelId,
        ?string $note,
        string $name,
        string $email,
        string $phoneNumber,
        string $paymentMethod,
        array $roomTypeReservedList,
        float $totalPrice,
        float $tax,
        float $vat,
        string $checkInDay,
        string $checkOutDay
    ) {
        $this->hotelId = $hotelId;
        $this->note = $note;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->paymentMethod = $paymentMethod;
        $this->totalPrice = $totalPrice;
        $this->tax = $tax;
        $this->vat = $vat;
        $this->checkInDay = $checkInDay;
        $this->checkOutDay = $checkOutDay;

        foreach ($roomTypeReservedList as $room) {
            $this->roomTypeReservedList[] = new RoomTypeReservedDTO(
                $room['id'],
                $room['count'],
                $room['price']
            );
        }
    }
}
