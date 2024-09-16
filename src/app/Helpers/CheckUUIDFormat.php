<?php

namespace App\Helpers;

class CheckUUIDFormat
{

    public static function isValidUuid(mixed $uuid): bool
    {
        return is_string($uuid) && preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid);
    }
}
