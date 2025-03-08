<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breed;

class BreedController extends Controller
{
    public function index()
    {
        $breeds = Breed::all();
        return response()->json($breeds);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pet_type_id' => 'required|exists:pet_types,id',
        ]);

        $breed = Breed::create($validatedData);

        return response()->json($breed, 201);
    }

    public function show(Breed $breed)
    {
        return response()->json($breed);
    }

    public function update(Request $request, Breed $breed)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'pet_type_id' => 'nullable|exists:pet_types,id',
        ]);

        $breed->update($validatedData);

        return response()->json($breed);
    }

    public function destroy(Breed $breed)
    {
        $breed->delete();
        return response()->json(null, 204);
    }
}
