<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Views;
use Illuminate\Support\Str;

class ViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Views::insert([
            [
                'id' => Str::uuid(),
                'name' => 'Beach View'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Mountain View'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'City View'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Garden View'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Lake View'
            ],
        ]);
    }
}
