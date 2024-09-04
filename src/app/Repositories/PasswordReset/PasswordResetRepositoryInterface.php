<?php

namespace App\Repositories\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function createOTP($email);

    public function getOTP($email, $otp);
}
