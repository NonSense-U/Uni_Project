<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/order',[CustomerController::class, 'place_order'])->middleware('auth:sanctum','role:customer');
