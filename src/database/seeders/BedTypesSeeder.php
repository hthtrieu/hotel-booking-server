<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BedTypes;
use Illuminate\Support\Str;

class BedTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create bed types
        BedTypes::insert([
            [
                'id' => Str::uuid(),
                'name' => 'Single Bed',
                'description' => 'A bed for one person.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Double Bed',
                'description' => 'A bed for two persons.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Queen Bed',
                'description' => 'A larger bed for two persons, typically measuring 60 inches wide by 80 inches long.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'King Bed',
                'description' => 'A very large bed for two persons, typically measuring 76 inches wide by 80 inches long.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Twin Bed',
                'description' => 'Two single beds, often used in rooms with multiple persons.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Bunk Bed',
                'description' => 'A set of two single beds stacked on top of each other.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sofa Bed',
                'description' => 'A convertible sofa that can be transformed into a bed.'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Rollaway Bed',
                'description' => 'A portable bed that can be folded and stored when not in use.'
            ]
        ]);
    }
}
