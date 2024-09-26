<?php

namespace App\Repositories\Hotel;

use App\Repositories\BaseRepositoryInterface;

interface IHotelRepo extends BaseRepositoryInterface
{
    public function getHotelsList(array $query);

    public function getHotelById(string $id);
}
