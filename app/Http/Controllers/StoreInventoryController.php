<?php

namespace App\Http\Controllers;

use App\Models\StoreInventory;
use App\Http\Requests\StoreStoreInventoryRequest;
use App\Http\Requests\UpdateStoreInventoryRequest;
use App\Models\Product;
use App\Models\Store;
use App\Notifications\InventoryNotification;
use Illuminate\Http\Request;

class StoreInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $store = Store::find($request->store_id);

        return response()->json([$store->inventories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $store = Store::find($request->store_id);

        if (!$store) {
            return response()->json(["message" => "there is no store with an id of {$request->store_id} !"], 404);
        }

        if ($request->user()->storeOwner->id != $store->store_owner_id) {
            return response()->json(["message" => "SHOO! You are not the Owner of this Store!"]);
        }

        $request->validate([
            'price' => ['integer'],
            'quantity' => ['integer'],
            'productName' => ['string', 'max:255']
        ]);

        $product  = Product::where('name', $request->productName)->first();

        if (!$product) {
            $product = Product::create([
                'name' => $request->productName
            ]);
        }

        $storeInventory = StoreInventory::create([
            'store_id' => $request->store_id,
            'product_id' => $product->id,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        return response()->json(['message' => "{$request->productName} was successfully added to your inventory! "], 202);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreInventoryRequest $request, StoreInventory $storeInventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreInventory $storeInventory)
    {
        //
    }

    public function check_stock(StoreInventory $storeInventory,string $action)
    {
        //! notification should be sent to the store owner
        $storeInventory->store->store_owner->user->notify(new InventoryNotification($action));
    }
}
