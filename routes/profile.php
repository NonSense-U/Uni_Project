<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::patch('/profile',[AccountController::class, 'update'])->middleware('auth:sanctum');

Route::get('/test',[AccountController::class, 'test']);

Route::post('/upload-pic',[AccountController::class, 'upload_pic'])->middleware('auth:sanctum');

Route::post('/delete-pic',[AccountController::class, 'delete_pic'])->middleware('auth:sanctum');

Route::delete('/profile',[AccountController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/notifications',[NotificationsController::class, 'get_notifications'])->middleware('auth:sanctum');
