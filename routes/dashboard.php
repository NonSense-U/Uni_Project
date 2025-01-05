<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreOwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard',[DashboardController::class,'get_view'])->middleware('auth:sanctum')->name('dashboard.home');
