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
            'Labrador Retriever',
            'German Shepherd',
            'Golden Retriever',
            'French Bulldog',
            'Bulldog',
            'Poodle',
            'Beagle',
            'Rottweiler',
            'Dachshund',
            'Siberian Husky',
            'Pitbull',
            'Mastiff'
        ];

        foreach ($dogBreeds as $breedName) {
            Breed::create([
                'name' => $breedName,
                'pet_type_id' => $dogType->id,
            ]);
        }
    }
}
