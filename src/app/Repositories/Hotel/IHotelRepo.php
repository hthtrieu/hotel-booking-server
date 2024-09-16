<?php

namespace App\Repositories\Hotel;


interface IHotelRepo
{
    public function getHotelsList(array $query);

    public function getHotelById(string $id);
}
