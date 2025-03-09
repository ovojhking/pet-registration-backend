<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Breed;
use App\Models\PetType;

class DogBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dogType = PetType::where('name', 'dog')->first();

        $dogBreeds = [
            'labrador_retriever',
            'german_shepherd',
            'golden_retriever',
            'french_bulldog',
            'bulldog',
            'poodle',
            'beagle',
            'rottweiler',
            'dachshund',
            'siberian_husky',
            'pitbull',
            'mastiff'
        ];

        foreach ($dogBreeds as $breedName) {
            Breed::create([
                'name' => $breedName,
                'pet_type_id' => $dogType->id,
            ]);
        }
    }
}
