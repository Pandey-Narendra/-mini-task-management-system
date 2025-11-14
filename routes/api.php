<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('auth/test', function() {
    return response()->json([
        'status' => true,
        'message' => 'API is working.',
    ], 200);
});

// Route::post('/register', [AuthController::class, 'register']);

Route::prefix('auth')->group(function () {

    // Public API routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::get('/profile',  [AuthController::class, 'profile']);
    });

});