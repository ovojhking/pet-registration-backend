<?php

namespace App\Services;

use App\Models\Breed;

class DangerousBreedServiceForMix extends DangerousBreedService
{
    /**
     * Check if a custom breed name contains a dangerous breed
     */
    public function isDangerousCustomBreed($customBreed)
    {
        $customBreed = strtolower($customBreed);
        
        foreach (self::DANGEROUS_BREEDS as $dangerousBreed) {
            if (strpos($customBreed, strtolower($dangerousBreed)) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * This method extends the base class functionality to support mixed breeds.
     * @param array $data Must contain 'is_mix', pet_type_id and the base method's required parameters.
     * is_unknown or custom_breed are required when is_mix is true.
     * @return bool
     */
    public function checkIfDangerous(array $data) 
    {
        if(!isset($data['is_mix']) || !isset($data['pet_type_id'])) {
            throw new \Exception('Error: Missing required parameters: is_mix and pet_type_id are required.');
        }

        // add custom breed check for mixed breeds
        if ($data['is_mix']) {
            if (!isset($data['custom_breed']) && (!isset($data['is_unknown']) || $data['is_unknown'] === false)) {
                throw new \Exception('Error: Missing required parameters: custom_breed or is_unknown');
            }

            if (
                !$this->hasDangerousBreeds($data['pet_type_id']) || 
                (isset($data['is_unknown']) && $data['is_unknown'] === true)
            ) {
                return false;
            }
            return $this->isDangerousCustomBreed($data['custom_breed']);
        }else{
            // use the base class method to check for dangerous breeds
            return parent::checkIfDangerous($data);
        }
    }
}
