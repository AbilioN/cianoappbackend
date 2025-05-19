<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AquariumController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
    Route::post('aquarium/add-consumable', [AquariumController::class, 'addConsumable']);
    Route::get('notifications', [NotificationController::class, 'getNotifications']);
    Route::get('notification/{slug}', [NotificationController::class, 'getNotification']);
    Route::post('notification/activate', [NotificationController::class, 'activateNotification']);
    Route::get('aquarium-notifications/{aquariumSlug}', [NotificationController::class, 'getAquariumNotifications']);
    Route::put('notification/deactivate', [NotificationController::class, 'deactiveNotification']);
    Route::post('notification/read', [NotificationController::class, 'readNotification']);
    Route::delete('aquarium-notifications/delete', [NotificationController::class, 'deleteNotification']);
    Route::get('/guides', [GuideController::class, 'getGuides']);
    Route::get('/guides/{language}', [GuideController::class, 'getGuidesByLanguage']);
    Route::get('products', [ProductController::class, 'getProducts']);
    Route::get('products/{language}', [ProductController::class, 'getProductsByLanguage']);
    Route::delete('/account/delete', [AuthController::class, 'deleteAccount']);

    // Adicione outras rotas autenticadas aqui
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    // Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/app-version', function () {


    
    return response()->json([
        'success' => true,
        'version' => '1.1.3',
    ]);
});
