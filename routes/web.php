<?php

use App\Http\Controllers\CodeChallengeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/challenge/rom-to-int', [CodeChallengeController::class, 'roma_to_int']);

// clear cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
