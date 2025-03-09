<?php

namespace App\Services;

use App\Models\PetType;
use App\Models\Breed;

class DangerousBreedService
{
    const DANGEROUS_BREEDS = ['pitbull', 'mastiff', 'rottweiler'];

    public function hasDangerousBreeds($petTypeId)
    {
        $petType = PetType::find($petTypeId);

        // if petType is not a dog, return false immediately
        if (!$petType || strtolower($petType->name) !== 'dog') {
            return false;
        }

        return true;
    }

    public function isDangerousBreed($breedName)
    {
        return in_array(strtolower($breedName), array_map('strtolower', self::DANGEROUS_BREEDS));
    }

    /**
     * Check if the pet is dangerous.
     *
     * @param array $data Must contain 'pet_type_id'.
     * @return bool
     */

    public function checkIfDangerous(array $data)
    {
        if(!isset($data['pet_type_id'])) {
            throw new \Exception('Error: Missing required parameters: pet_type_id is required.');
        }

        if (!$this->hasDangerousBreeds($data['pet_type_id'])) {
            return false;
        }

        if ($data['breed_id']) {
            $breed = Breed::find($data['breed_id']);
            return $breed && $this->isDangerousBreed($breed->name);
        }

        return false;
    }
}
