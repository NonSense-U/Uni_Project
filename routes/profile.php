<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::post('/profile',[AccountController::class, 'update'])->middleware('auth:sanctum');

Route::get('/test',[AccountController::class, 'test']);

Route::post('/upload-pic',[AccountController::class, 'upload_pic'])
->middleware('auth:sanctum');

Route::post('/delete-pic',[AccountController::class, 'delete_pic'])
->middleware('auth:sanctum');
