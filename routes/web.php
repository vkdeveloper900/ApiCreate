<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Index\WelcomeController;
use App\Http\Controllers\ThirdPartyApi\ApiDataController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');   // Show login form
Route::post('/login', [AuthController::class, 'login']);

Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('signUp');   // Show signup form
Route::post('/signup', [AuthController::class, 'signUp']);


Route::middleware('auth:web')->prefix('admin')->group(function () {

    Route::get('logout',[AuthController::class,'logOut'])->name('admin.logOut');
    Route::get('welcome', [WelcomeController::class, 'welcome'])->name('admin.welcome');

    Route::get('albums', [WelcomeController::class, 'albums'])->name('admin.albums');
    Route::get('albums/photos', [WelcomeController::class, 'photos'])->name('admin.album.photos');






    Route::get('/import-albums-photos', [ApiDataController::class, 'fetchAndSave'])->name('apiFetchAndSave');
});
