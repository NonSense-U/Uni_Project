<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $validated = $request->validate([
            'search_key' => ['string', 'max:255']
        ]);

        $response = [];

        if ($request->store) {
            array_push($response, ...Store::where('storeName', 'like', '%' . $validated['search_key'] . '%')->get());
        }

        if ($request->product) {
            $products = Product::where('productName', 'like', '%' . $validated['search_key'] . '%')->get();

            foreach ($products as $product) {
                array_push($response, $product->stores);
            }
        }

        return response()->json($response, 200);
    }
}
