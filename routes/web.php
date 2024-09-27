<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;

Route::get('/', function () {
  
    return view('UserLogin');
});

// Route::get('/', function () {

//     return view('UserLogin');
// });

Route::get('/UserRegistration', [UserController::class, 'showRegistrationForm'])->name('UserRegistration');
Route::post('/UserRegistration', [UserController::class, 'register']);

Route::get('/UserLogin', [UserController::class, 'UserLogin'])->name('UserLogin');
Route::post('/UserLogin', [UserController::class, 'login']);

Route::get('include.Header', function () {
 
    return view('Header');
});
Route::get('/Home', function () {
    return view('Home');
});

// Route::group(['middleware' => ['auth']], function() { // Ensure user is authenticated
//     Route::get('/create/{parentId?}', [FolderController::class, 'create'])->name('create');
//     Route::post('/store', [FolderController::class, 'store'])->name('store');
//     // Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
//     // Route::get('/folders/{id}', [FolderController::class, 'show'])->name('folders.show');
//     // Route::put('/folders/{id}', [FolderController::class, 'update'])->name('folders.update');
//     // Route::delete('/folders/{id}', [FolderController::class, 'destroy'])->name('folders.destroy');
// });

Route::group(['middleware' => ['auth']], function() {
    // Display folder creation form
    Route::get('/create/{parentId?}', [FolderController::class, 'create'])->name('create');

    // Handle form submission (POST request)
    Route::post('/store', [FolderController::class, 'store'])->name('store');
});

// Route::get('/folders/create/{parentId?}', [FolderController::class, 'create'])->name('folders.create');
// Route::post('/folders/store', [FolderController::class, 'store'])->name('folders.store');


// Route::group(['middleware' => ['auth']], function() { // Ensure user is authenticated
//     Route::get('/folders/{folderId}/files/create', [FileController::class, 'create'])->name('files.create');
//     Route::post('/files', [FileController::class, 'store'])->name('files.store');
//     Route::get('/folders/{folderId}/files', [FileController::class, 'index'])->name('files.index');
//     Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');
// });

Route::get('/CreateFolder', function () {
    return view('CreateFolder');
});
Route::get('/indexFolder', [FolderController::class, 'index'])->name('indexFolder');
// Route::get('/indexFolder', function () {
//     return view('indexFolder');
// });

Route::get('/ShowFolders', function () {
    return view('ShowFolders');
});

// Handle logout request
Route::post('/logout', [UserController::class, 'logout'])->name('logout');