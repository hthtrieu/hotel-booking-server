<?php

namespace App\Dtos\User;

class UserDTO
{


    public function __construct(
        public string $id,
        public string $email,
        public string $name,
        public string $phone_number,
        public string $role,
    ) {}
    public static function fromModel($data)
    {
        return new self(
            $data->id,
            $data->email,
            $data->name,
            $data->phone_number,
            $data->role->value,
        );
    }
}
