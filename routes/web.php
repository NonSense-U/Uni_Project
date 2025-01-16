<?php

use App\Models\User;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
require __DIR__.'/basic.php';
require __DIR__.'/customer.php';
require __DIR__.'/dashboard.php';

Route::get('/', function () {
    return response()->json(['name'=>'lol']);
});

Route::get('/test',function()
{
    try {
        $files = Storage::disk('google')->allfiles();
        return response()->json($files);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/notify',function(Request $request)
{
    $user = $request->user();
    $user->notify(new OrderNotification());
    return response()->json('Notification Delivered !');
})->middleware('auth:sanctum');


Route::get('/markread',function(Request $request){
    $user = $request->user();
    $user->unreadNotifications->markAsRead();
    return response()->json([$user->notifications],200);
})->middleware('auth:sanctum');

Route::get('/test_dashboard',function()
{
    return view('auth.login');
});


Route::get('/create_store',function(Request $request){
    return view('store.create',['request' => $request]);
})->middleware('auth:sanctum')->name('store.create_page');


Route::get('/My_stores',function()
{
    $stores = Auth::user()->storeOwner->stores;
return view('store.index',['stores'=>$stores]);
});
