<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

//Ruta de test
Route::get('personnel', [TestController::class, 'index']);

Route::resource('personnel', PersonnelController::class)->only([
    'store', 'update', 'destroy'
]);

Route::post('personnel/api', [PersonnelController::class, 'personnelApi']);

