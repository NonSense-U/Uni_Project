<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/register',function()
{
    return view('auth.register');
});

Route::post('/register', [AccountController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::get('/login',function(){
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'generateToken'])->name('login');

Route::get('/getTokens',function(Request $request){
dd($request->user()->tokens);
})->middleware('auth:sanctum');


Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');
