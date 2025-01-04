<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all();
        return response()->json($stores);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'storeName' => ['string','max:255'],
            'stars' => ['integer']
        ]);
        $store = Store::create([
            'store_owner_id' => $request->user()->StoreOwner->id,
            'storeName' => $request->storeName,
            'stars' => $request->stars
        ]);

        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $store = Store::find($request->id);
        if(!$store)
        {
            return response()->json(["message" => "There is no store with an id of {$request->id} !"],404);
        }
        return response()->json($store);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $store = Store::find($request->id);

        if(!$store)
        {
            return response()->json(["message" => "There is no store with an id of {$request->id} !"],404);
        }

        if($request->user()->storeOwner->id != $store->store_owner_id)
        {
            return response()->json(["message" => "SHOO! You are not the Owner of this Store!"]);
        }
        $store->delete();

        return response()->json(["message" => "Your store has been successfully deleted !"],);
    }

}
