<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\StoreInventory;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'firstName' => 'nullable|max:255',
            'lastName' => 'nullable|max:255',
            'location' => 'nullable|max:255'
        ]);

        $customer->fill($validatedData);

        $customer->Save();

        return response()->json([$customer]);
    }

    public function place_order(Request $request)
    {
        $validatedData = $request->validate([
            'inventory_id' => ['integer'],
            'quantity' => ['integer']
        ]);

        $inventory = StoreInventory::find($request->inventory_id);

        if(!$inventory)
        {
            return response()->json(['message' => 'inventory not found T_T'],404);
        }

        $order = Order::create([
            'customer_id' => $request->user()->customer->id,
            'store_inventory_id' => $inventory->id,
            'total_cost' => $request->quantity * $inventory->price
        ]);

        if($request->paid)
        {
            if($inventory->quantity < $request->quantity)
            {
                return response()->json(['message' => "There isn't enough {$inventory->product->name} in stock T_T "]);
            }
            $inventory->quantity-=$request->quantity;
            $inventory->save();

            if($inventory->quantity == 0)
            {
                $inventoryController = new StoreInventoryController();
                $inventoryController->check_stock($inventory,'empty');
            }

        }

        return response()->json([$order],202);
    }
}
