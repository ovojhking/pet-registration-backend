<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetType;
use App\Models\Breed;
use App\Services\DangerousBreedServiceForMix;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PetController extends Controller
{
    /**
     * Get all pets (Potential future use)
     */
    public function index()
    {
        return response()->json(Pet::all()); // Currently not required, can be enabled later
    }

    /**
     * Store a new pet record
     */
    public function store(Request $request, DangerousBreedServiceForMix $dangerousBreedService)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pet_type_id' => 'required|exists:pet_types,id',
            'breed_id' => 'nullable|exists:breeds,id',
            'date_of_birth' => 'nullable|date',
            'approximate_age' => 'nullable|integer|min:1|max:20',
            'gender' => 'required|in:male,female',
            'is_mix' => 'required|boolean',
            'custom_breed' => 'nullable|string|max:255',
            'is_unknown' => 'nullable|boolean',
        ]);

        // Ensure at least one age-related field is provided
        if (empty($validatedData['date_of_birth']) && empty($validatedData['approximate_age'])) {
            return response()->json(['error' => 'Either date_of_birth or approximate_age must be provided.'], 400);
        }

        // Validate based on is_mix value
        if ($validatedData['is_mix'] === false) {
            // When is_mix is false, breed_id is required
            if (empty($validatedData['breed_id'])) {
                return response()->json(['error' => 'breed_id is required when is_mix is false.'], 400);
            }
            if(!empty($validatedData['custom_breed']) || !empty($validatedData['is_unknown'])) {
                return response()->json(['error' => 'custom_breed and is_unknown should not be provided when is_mix is false.'], 400);
            }
        } else {
            // When is_mix is true, breed_id must not be provided
            if (!empty($validatedData['breed_id'])) {
                return response()->json(['error' => 'breed_id should not be provided when is_mix is true.'], 400);
            }

            // Ensure either custom_breed or is_unknown is provided, but not both
            if (empty($validatedData['custom_breed']) && (empty($validatedData['is_unknown']) || $validatedData['is_unknown'] === false)) {
                return response()->json(['error' => "Either 'custom_breed' or 'is_unknown' must be provided, but only one of them can be provided."], 400);
            }

            if (!empty($validatedData['custom_breed']) && (isset($validatedData['is_unknown']) && $validatedData['is_unknown'] === true)) {
                return response()->json(['error' => "Either 'custom_breed' or 'is_unknown' must be provided, but only one of them can be provided."], 400);
            }


        }

        // Determine the date of birth and whether it's estimated
        $isAgeEstimated = false;
        if (!empty($validatedData['date_of_birth'])) {
            $dateOfBirth = Carbon::parse($validatedData['date_of_birth'])->format('Y-m-d');
        } else {
            $dateOfBirth = Carbon::now()->subYears($validatedData['approximate_age'])->format('Y-m-d');
            $isAgeEstimated = true;
        }

        $isDangerous = $dangerousBreedService->checkIfDangerous($validatedData);

        $pet = Pet::create([
            'name' => $validatedData['name'],
            'pet_type_id' => $validatedData['pet_type_id'],
            'breed_id' => $validatedData['breed_id'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'is_age_estimated' => $isAgeEstimated,
            'gender' => $validatedData['gender'],
            'is_dangerous' => $isDangerous,
            'is_mix' => $validatedData['is_mix'],
            'custom_breed' => $validatedData['custom_breed'] ?? null,
            'is_unknown' => $validatedData['is_unknown'] ?? false,
        ]);
        $age = Carbon::parse($dateOfBirth)->diff(Carbon::now())->y;

        $breed = null;
        $breedName = null;
        if (isset($validatedData['breed_id']) && $validatedData['breed_id']) {
            $breed = Breed::find($validatedData['breed_id']);
            $breedName = $breed ? $breed->name : null;
        }

        $petType = PetType::find($validatedData['pet_type_id']);

        return response()->json([
            'name' => $pet->name,
            'type' => $petType->name,
            'breed' => $breedName,
            'date_of_birth' => $pet->date_of_birth,
            'age' => $age,
            'is_age_estimated' => $pet->is_age_estimated,
            'gender' => $pet->gender,
            'is_dangerous' => $pet->is_dangerous,
            'is_mix' => $pet->is_mix,
            'custom_breed' => $pet->custom_breed,
            'is_unknown' => $pet->is_unknown,
        ], 201);
    }

    /**
     * Get a single pet by ID (Potential future use)
     */
    public function show(Pet $pet)
    {
        return response()->json($pet); // Currently not required, can be enabled later
    }

    /**
     * Update pet information (Potential future use)
     */
    public function update(Request $request, Pet $pet, DangerousBreedServiceForMix $dangerousBreedService)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pet_type_id' => 'required|exists:pet_types,id',
            'breed_id' => 'nullable|exists:breeds,id',
            'date_of_birth' => 'nullable|date',
            'approximate_age' => 'nullable|integer|min:1|max:20',
            'gender' => 'required|in:male,female',
            'is_mix' => 'required|boolean',
            'custom_breed' => 'nullable|string|max:255',
            'is_unknown' => 'nullable|boolean',
        ]);

        // Ensure at least one age-related field is provided
        if (empty($validatedData['date_of_birth']) && empty($validatedData['approximate_age'])) {
            return response()->json(['error' => 'Either date_of_birth or approximate_age must be provided.'], 400);
        }

        // Validate based on is_mix value
        if ($validatedData['is_mix'] === false) {
            // When is_mix is false, breed_id is required
            if (empty($validatedData['breed_id'])) {
                return response()->json(['error' => 'breed_id is required when is_mix is false.'], 400);
            }
            if (!empty($validatedData['custom_breed']) || !empty($validatedData['is_unknown'])) {
                return response()->json(['error' => 'custom_breed and is_unknown should not be provided when is_mix is false.'], 400);
            }
        } else {
            // When is_mix is true, breed_id must not be provided
            if (!empty($validatedData['breed_id'])) {
                return response()->json(['error' => 'breed_id should not be provided when is_mix is true.'], 400);
            }

            // Ensure either custom_breed or is_unknown is provided, but not both
            if (empty($validatedData['custom_breed']) && (empty($validatedData['is_unknown']) || $validatedData['is_unknown'] === false)) {
                return response()->json(['error' => "Either 'custom_breed' or 'is_unknown' must be provided, but only one of them can be provided."], 400);
            }

            if (!empty($validatedData['custom_breed']) && (isset($validatedData['is_unknown']) && $validatedData['is_unknown'] === true)) {
                return response()->json(['error' => "Either 'custom_breed' or 'is_unknown' must be provided, but only one of them can be provided."], 400);
            }
        }

        // Determine the date of birth and whether it's estimated
        $isAgeEstimated = false;
        if (!empty($validatedData['date_of_birth'])) {
            $dateOfBirth = Carbon::parse($validatedData['date_of_birth'])->format('Y-m-d');
        } else {
            $dateOfBirth = Carbon::now()->subYears($validatedData['approximate_age'])->format('Y-m-d');
            $isAgeEstimated = true;
        }

        $isDangerous = $dangerousBreedService->checkIfDangerous($validatedData);

        // Update the pet's information
        $pet->update([
            'name' => $validatedData['name'],
            'pet_type_id' => $validatedData['pet_type_id'],
            'breed_id' => $validatedData['breed_id'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'is_age_estimated' => $isAgeEstimated,
            'gender' => $validatedData['gender'],
            'is_dangerous' => $isDangerous,
            'is_mix' => $validatedData['is_mix'],
            'custom_breed' => $validatedData['custom_breed'] ?? null,
            'is_unknown' => $validatedData['is_unknown'] ?? false,
        ]);

        $age = Carbon::parse($dateOfBirth)->diff(Carbon::now())->y;

        $breed = null;
        $breedName = null;
        if (isset($validatedData['breed_id']) && $validatedData['breed_id']) {
            $breed = Breed::find($validatedData['breed_id']);
            $breedName = $breed ? $breed->name : null;
        }

        $petType = PetType::find($validatedData['pet_type_id']);

        return response()->json([
            'name' => $pet->name,
            'type' => $petType->name,
            'breed' => $breedName,
            'date_of_birth' => $pet->date_of_birth,
            'age' => $age,
            'is_age_estimated' => $pet->is_age_estimated,
            'gender' => $pet->gender,
            'is_dangerous' => $pet->is_dangerous,
            'is_mix' => $pet->is_mix,
            'custom_breed' => $pet->custom_breed,
            'is_unknown' => $pet->is_unknown,
        ], 200);  // Currently not required, can be enabled later
    }

    /**
     * Delete a pet by ID (Potential future use)
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(null, 204); // Currently not required, can be enabled later
    }
}
