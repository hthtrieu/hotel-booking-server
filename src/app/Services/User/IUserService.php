<?php

namespace App\Services\User;

use App\Dtos\User\UpdateProfileRequestDTO;

interface IUserService
{
    public function getUserProfile();

    public function getUserHistoryReservation(string $reservationStatus);

    public function updateProfile(UpdateProfileRequestDTO $data);
}
