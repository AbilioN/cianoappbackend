<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AquariumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('create-aquarium', [AquariumController::class, 'createAquarium']);
    Route::get('aquariums', [AquariumController::class, 'getAquariums']);
    Route::get('aquarium/{id}', [AquariumController::class, 'getAquarium']);
    Route::put('update-aquarium', [AquariumController::class, 'updateAquarium']);
    Route::delete('delete-aquarium', [AquariumController::class, 'deleteAquarium']);
    // Adicione outras rotas autenticadas aqui
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
