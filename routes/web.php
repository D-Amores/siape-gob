<?php

use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PersonnelAssetController;
use App\Http\Controllers\Admin\PersonnelAssetPendingController;

Route::get('/', function () {
    return view('assets.asset');
})->name('assets.dashboard');

//Ruta de test
//Route::get('personnel', [TestController::class, 'index'])->name('personnel.index');

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
Route::resource('personnel-asset', PersonnelAssetController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('personnel-asset-pending', PersonnelAssetPendingController::class)->only(['index', 'store', 'update', 'destroy']);

Route::post('brands/api', [BrandController::class, 'brandApi']);
Route::post('categories/api', [CategoryController::class, 'categoryApi']);
// Route::post('personnel-asset/api', [PersonnelAssetController::class, 'personnelAssetApi']);
Route::post('personnel-asset-pending/api', [PersonnelAssetPendingController::class, 'personnelAssetPendingApi']);

//RUTA PARA EL CRUD DE PERSONAL
Route::resource('admin/personnel', PersonnelController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
Route::post('admin/personnel/api', [PersonnelController::class, 'personnelApi']);


//RUTA PARA EL CRUD DE USUARIOS
Route::resource('admin/users', UserController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
Route::post('admin/users/api', [UserController::class, 'userApi']);

//RUTA PARA EL CRUD DE AREAS
Route::post('admin/areas/api', [AreaController::class, 'areaApi']);

Route::post('select-assets/api', [AssetController::class, 'selectAssetsApi']);
// Ruta para obtener assets para la tabla o detalles
Route::get('assets/api', [AssetController::class, 'assetsApi'])->name('assets.api');

