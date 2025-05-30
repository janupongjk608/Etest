<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('profiles', [ProfileController::class, 'index']);
Route::get('profiles/{id}', [ProfileController::class, 'show']);
Route::post('profiles', [ProfileController::class, 'store']);
Route::put('profiles/{id}', [ProfileController::class, 'update']);
Route::delete('profiles/{id}', [ProfileController::class, 'destroy']);
Route::get('profiles/agereport', [ProfileController::class, 'ageReport']);
