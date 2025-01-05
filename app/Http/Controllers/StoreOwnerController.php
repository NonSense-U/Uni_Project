<?php

namespace App\Http\Controllers;

use App\Models\StoreOwner;
use App\Http\Requests\StoreStoreOwnerRequest;
use App\Http\Requests\UpdateStoreOwnerRequest;
use App\Models\Product;
use App\Models\StoreInventory;
use Illuminate\Http\Request;

class StoreOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('storeOwnerDashboard', ['request' => $request]);
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
    public function store(StoreStoreOwnerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StoreOwner $storeOwner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreOwner $storeOwner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreOwnerRequest $request, StoreOwner $storeOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreOwner $storeOwner)
    {
        //
    }

    public function add_inventory(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'store_id' => 'required|exists:stores,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::firstOrCreate(
            ['name' => $validated['product_name']],
            // ['other_column' => $request->get('other_value', 'default')]
        );

        $inventory = StoreInventory::updateOrCreate(
            [
                'store_id' => $validated['store_id'],
                'product_id' => $product->id,
            ],
            [
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
            ]
        );

        return response()->json(['message' => 'Inventory updated successfully.', 'inventory' => $inventory]);
    }
}
