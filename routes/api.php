<?php

use App\Http\Controllers\API\Admin\AuthController;
use App\Http\Controllers\API\Admin\Profile\ProfileController;
use App\Http\Controllers\API\Admin\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->prefix('profile')->group(function (){
        Route::get('profile',[ProfileController::class,'profile']);
        Route::post('update',[ProfileController::class,'update']);
    });

    Route::middleware('auth:sanctum')->prefix('user')->group(function (){
        Route::get('list',[UserController::class,'list']);
    });


});





