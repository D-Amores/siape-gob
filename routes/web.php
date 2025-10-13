<?php

use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PersonalAssetController;
use App\Http\Controllers\PersonalAssetPendingController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('layouts.layout');
})->name('dashboard');

//Ruta de test
Route::get('personnel', [TestController::class, 'index'])->name('personnel.index');

Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::resource('assets', AssetController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('brands', BrandController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('personal-asset', PersonalAssetController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('personal-asset-pending', PersonalAssetPendingController::class)->only(['index', 'store', 'update', 'destroy']);

Route::post('brands/api', [BrandController::class, 'brandApi']);

//RUTA PARA EL CRUD DE PERSONAL
Route::resource('personnel', PersonnelController::class)->only([
    'store', 'update', 'destroy'
]);
Route::post('personnel/api', [PersonnelController::class, 'personnelApi']);


//RUTA PARA EL CRUD DE USUARIOS
Route::resource('users', UserController::class)->only([
    'store', 'update', 'destroy'
]);
Route::post('users/api', [UserController::class, 'userApi']);
