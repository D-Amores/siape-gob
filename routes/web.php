<?php

use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('assets', AssetController::class)->only(['index', 'store', 'update', 'destroy']);
