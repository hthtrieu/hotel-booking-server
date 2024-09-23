<?php

namespace App\Dtos\Room;

class RoomAvailableOption
{
    public string $roomTypeId;
    public ?int $roomCount;
    public ?\DateTime $checkinDay;
    public ?\DateTime $checkoutDay;

    public function __construct(string $roomTypeId, ?int $roomCount = null, ?\DateTime $startDay = null, ?\DateTime $endDay = null)
    {
        $this->roomTypeId = $roomTypeId;
        $this->roomCount = $roomCount;   // Giá trị mặc định là null
        $this->checkinDay = $startDay;     // Giá trị mặc định là null
        $this->checkoutDay = $endDay;         // Giá trị mặc định là null
    }
}
