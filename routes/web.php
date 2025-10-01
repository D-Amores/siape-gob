<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonnelController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('personnel', PersonnelController::class)->only([
    'index', 'store'
]);