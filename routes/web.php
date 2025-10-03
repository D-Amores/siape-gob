<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

//Ruta de test
Route::get('personnel', [TestController::class, 'index']);
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

