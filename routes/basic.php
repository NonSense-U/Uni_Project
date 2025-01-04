<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/stores',[StoreController::class, 'index']);

Route::post('/store',[StoreController::class, 'store'])->middleware('role:store_owner','auth:sanctum');

Route::get('/store/{id}', [StoreController::class, 'getStoreById']);

Route::delete('/store/{id}',[StoreController::class, 'destroy'])->middleware('role:store_owner','auth:sanctum');
