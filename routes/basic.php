<?php

use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreInventoryController;
use Illuminate\Support\Facades\Route;


//! Store Crud

Route::get('/stores',[StoreController::class, 'index']);
Route::post('/store',[StoreController::class, 'store'])->middleware('role:store_owner','auth:sanctum');
Route::get('/store/search',[StoreController::class, 'searchByName']);
Route::get('/store/{id}', [StoreController::class, 'show']);
Route::delete('/store/{id}',[StoreController::class, 'destroy'])->middleware('role:store_owner','auth:sanctum');

//! StoreInventory

Route::get('store/{store_id}/inventories',[StoreInventoryController::class, 'index']);
Route::post('store/{store_id}',[StoreInventoryController::class, 'store'])->middleware('role:store_owner','auth:sanctum');
