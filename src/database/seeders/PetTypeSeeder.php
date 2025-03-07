<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PetType;

class PetTypeSeeder extends Seeder
{
    public function run()
    {
        $petTypes = ['dog', 'cat'];

        foreach ($petTypes as $type) {
            PetType::firstOrCreate(['name' => $type]);
        }
    }
}
