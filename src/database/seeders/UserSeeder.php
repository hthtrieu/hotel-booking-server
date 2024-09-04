<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'id' => Str::uuid(),
                'name' => 'admin',
                'email' => 'admin@admin.vn',
                'password' => bcrypt('password'),
                'role' => RoleEnum::ADMINISTRATOR->value,
                'address' => 'Vietnam',
                'phone_number' => '0123456789',
                'created_at' => Carbon::now(config('app.timezone')),
                'created_by' => 'seeder',
            ],
        ]);
    }
}
