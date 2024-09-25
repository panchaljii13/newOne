<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('UserLogin');
// });


Route::get('/UserRegistration', [UserController::class, 'showRegistrationForm'])->name('UserRegistration');
Route::post('/UserRegistration', [UserController::class, 'register']);

Route::get('/UserLogin', [UserController::class, 'UserLogin'])->name('UserLogin');
Route::post('/UserLogin', [UserController::class, 'login']);
