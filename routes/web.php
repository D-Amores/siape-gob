<?php

use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('assets', AssetController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('personnel', PersonnelController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

Route::post('personnel/api', [PersonnelController::class, 'personnelApi']);
