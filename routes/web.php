<?php

use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\BrandController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('assets', AssetController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('brands', BrandController::class)->only(['index', 'store', 'update', 'destroy']);

Route::resource('personnel', PersonnelController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

Route::post('personnel/api', [PersonnelController::class, 'personnelApi']);
