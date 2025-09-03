<?php

use App\Http\Controllers\Api\User\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', [App\Http\Controllers\Api\HomeController::class, 'home']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [ProfileController::class, 'show']);
});
