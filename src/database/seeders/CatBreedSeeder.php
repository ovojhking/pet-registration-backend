<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PetType;
use App\Models\Breed;

class CatBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catType = PetType::where('name', 'cat')->first();
        
        $catBreeds = ['persian', 'maine_coon', 'siamese', 'ragdoll', 'bengal'];

        foreach ($catBreeds as $breedName) {
            Breed::create([
                'name' => $breedName,
                'pet_type_id' => $catType->id,
            ]);
        }
    }
}
