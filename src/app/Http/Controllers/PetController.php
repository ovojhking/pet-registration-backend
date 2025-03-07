<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetType;
use App\Models\Breed;
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pet_type_id' => 'required|exists:pet_types,id',
            'breed_id' => 'required|exists:breeds,id',
            'date_of_birth' => 'nullable|date',
            'approximate_age' => 'nullable|integer|min:1|max:20',
            'gender' => 'required|in:male,female',
        ]);
        // Ensure at least one age-related field is provided
        if (empty($validatedData['date_of_birth']) && empty($validatedData['approximate_age'])) {
            return response()->json(['error' => 'Either date_of_birth or approximate_age must be provided.'], 400);
        }

        // Determine the date of birth and whether it's estimated
        $isAgeEstimated = false;
        if (!empty($validatedData['date_of_birth'])) {
            $dateOfBirth = Carbon::parse($validatedData['date_of_birth'])->format('Y-m-d');
        } else {
            $dateOfBirth = Carbon::now()->subYears($validatedData['approximate_age'])->format('Y-m-d');
            $isAgeEstimated = true;
        }

        $pet = Pet::create([
            'name' => $validatedData['name'],
            'pet_type_id' => $validatedData['pet_type_id'],
            'breed_id' => $validatedData['breed_id'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'is_age_estimated' => $isAgeEstimated,
            'gender' => $validatedData['gender'],
        ]);
        $age = Carbon::parse($dateOfBirth)->diff(Carbon::now())->y;

        $breed = Breed::find($validatedData['breed_id']);
        $petType = PetType::find($validatedData['pet_type_id']);

        return response()->json([
            'name' => $pet->name,
            'type' => $petType->name,
            'breed' => $breed->name,
            'date_of_birth' => $pet->date_of_birth,
            'age' => $age,
            'is_age_estimated' => $pet->is_age_estimated,
            'gender' => $pet->gender,
            'is_dangerous' => $pet->is_dangerous,
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
    public function update(Request $request, Pet $pet)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'pet_type_id' => 'exists:pet_types,id',
            'breed_id' => 'exists:breeds,id',
            'date_of_birth' => 'nullable|date',
            'approximate_age' => 'nullable|integer|min:1|max:20',
            'gender' => 'in:male,female',
            'is_dangerous' => 'nullable|boolean',
        ]);
    
        // Determine if we are updating with approximate age
        $isAgeEstimated = false;
        if (isset($validatedData['date_of_birth'])) {
            // If date_of_birth is provided, use it and clear approximate_age if needed
            $pet->date_of_birth = Carbon::parse($validatedData['date_of_birth'])->format('Y-m-d');
        } elseif (isset($validatedData['approximate_age'])) {
            // If approximate_age is provided, calculate the estimated date_of_birth
            $pet->date_of_birth = Carbon::now()->subYears($validatedData['approximate_age'])->format('Y-m-d');
            $isAgeEstimated = true;
        }
    
        // Update other fields, including the estimated status
        $pet->is_age_estimated = $isAgeEstimated;
        $pet->update($validatedData);
    
        return response()->json($pet); // Currently not required, can be enabled later
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
