<?php

namespace App\Dtos\User;

class UpdateProfileRequestDTO
{
    public string $id;
    public string $email;
    public string $name;
    public string $phone_number;

    public function __construct(string $id, string $email, string $name, string $phone_number)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->phone_number = $phone_number;
    }
}
