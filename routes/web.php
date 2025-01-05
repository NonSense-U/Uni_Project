<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
require __DIR__.'/basic.php';
require __DIR__.'/customer.php';

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
