<?php

use App\Http\Controllers\PetController;
use App\Http\Controllers\BreedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('pets', PetController::class);
Route::apiResource('breeds', BreedController::class);