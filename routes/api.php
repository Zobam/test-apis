<?php

use App\Http\Controllers\Api\EndpointController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\TestSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resources([
    'endpoints' => EndpointController::class,
    'test-settings' => TestSettingController::class,
]);
Route::get('/test', [TestController::class, 'index']);
