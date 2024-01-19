<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\EventRegistrationController;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/event', [EventController::class, 'create']);
    Route::put('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);
    Route::apiResource('registration', EventRegistrationController::class);
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'currentUser']);
});
