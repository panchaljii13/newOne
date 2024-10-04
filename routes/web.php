<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\ProfileController;


Route::get('/ShpwProfile', function () {
    return view('ShpwProfile');
});

Route::get('/downloadHistory', function () {
    return view('downloadHistory');
});

Route::get('/', function () {

    return view('UserLogin');
});


Route::get('/UserRegistration', [UserController::class, 'showRegistrationForm'])->name('UserRegistration');
Route::post('/UserRegistration', [UserController::class, 'register']);

Route::get('/UserLogin', [UserController::class, 'UserLogin'])->name('UserLogin');
Route::post('/UserLogin', [UserController::class, 'login']);

Route::get('include.Header', function () {

    return view('Header');
});


//-------------------------------------------Show Home -----------------


Route::get('/Home', [HomeController::class, 'Homeindex'])->name('Home');

// Route::get('/Home', function () {
//     return view('Home');
// });

// -------------------------------------------create Folder -----------------

Route::group(['middleware' => ['auth']], function() {
    // Display folder creation form
    Route::get('/create/{parentId?}', [FolderController::class, 'create'])->name('create');
    // Route::get('/folders/{id}', [FolderController::class, 'show'])->name('viewFolder');

    // Handle form submission (POST request)
    Route::post('/store', [FolderController::class, 'store'])->name('store');

});
//--------------------------------------------Show All Folders -----------------


Route::get('/indexFolder', [FolderController::class, 'index'])->name('indexFolder');
//--------------------------------------------Show Public Folders Home-----------------

Route::get('/{id}', [FolderController::class, 'show'])->name('show');
// ----------------------------------------------Update Folder -----------------

// Route to display the edit folder form
Route::get('{id}/editFolder', [FolderController::class, 'edit'])->name('editFolder');

// Route to handle the update request
Route::put('/folders/{id}', [FolderController::class, 'update'])->name('updateFolder');


// -------------------------------------------------Delete Folder -----------------

Route::delete('/destroy/{id}', [FolderController::class, 'destroy'])->name('destroy');


// ---------------------------------------------------Show public  Folder -----------------

Route::put('{id}/toggle-public', [FolderController::class, 'togglePublic'])->name('togglePublic');
Route::get('/Home', [FolderController::class, 'public'])->name('public');

// --------------------------------------------------Uplode Files  -----------------
Route::post('/uploadFile/{id}', [FileController::class, 'uploadFile'])->name('uploadFile');
// ----------------------------------------------------Delete Files  -----------------
Route::delete('/file/{id}', [FileController::class, 'destroy'])->name('deleteFile');



// -------------------------------------------------Downlord  Folder -----------------
Route::get('/download/{folder}', [FolderController::class, 'download'])->name('download');
// --------------------------------------------Show Downlord  Historys -----------------
Route::get('/downloadHistory', [FolderController::class, 'showDownloadHistory'])->name('downloadHistory');



// Route::get('/Pfl', function () {
//     return view('Pf');
// });

// Route::get('/Myprofile', function () {
//     return view('Myprofile');
// });
// Route::get('/Myprofile', [ProfileController::class, 'show'])->name('Myprofile');


// ------------------------------------------------Log-Out -----------------
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Route::get('Profile', function () {

//     return view('Profile');
// });

// -----------------------------------------------Admin-----------------

// Admin routes protected by SuperAdmin middleware
Route::group(['middleware' => ['auth', 'superAdmin']], function () {
    Route::get('/admin/dashboard', [SuperAdminMiddleware::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/manage-users', [SuperAdminMiddleware::class, 'manageUsers'])->name('manage.users');
    // Add more admin routes as needed
});

