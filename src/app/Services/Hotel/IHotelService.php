<?php

namespace App\Services\Hotel;

use App\Dtos\Hotel\HotelDTO;
use App\Http\Requests\Hotels\HotelSearchRequest;

interface IHotelService
{
    public function getHotelsList(HotelSearchRequest $request);

    public function getHotelById(string $id): HotelDTO;
}
