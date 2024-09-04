<?php

namespace App\Repositories\PasswordReset;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    private string $table = 'password_reset_otps';

    public function createOTP($email)
    {
        if ((bool) DB::table($this->table)->where('email', $email)->first()) {
            return $this->updateOTP($email);
        }

        $otp = rand(10000, 99999);
        $this->addOTP($otp, $email);

        return $otp;
    }

    private function addOTP($otp, $email)
    {
        DB::table($this->table)->insert([
            'id' => Str::uuid(),
            'email' => $email,
            'otp' => $otp,
            'created_at' => Carbon::now(config('app.timezone')),
        ]);
    }

    private function updateOTP($email)
    {
        $otp = rand(10000, 99999);
        DB::table($this->table)->where('email', $email)->update([
            'otp' => $otp,
            'updated_at' => Carbon::now(config('app.timezone')),
        ]);

        return $otp;
    }

    public function getOTP($email, $otp)
    {
        return DB::table($this->table)->where([
            'email' => $email,
            'otp' => $otp,
        ]);
    }
}
