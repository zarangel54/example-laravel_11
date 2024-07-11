<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// routes/api.php



Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

