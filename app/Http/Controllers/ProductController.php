<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function product_inventories(Request $request)
    {
        $product = Product::find($request->product_id);
        
        return response()->json($product->stores);
    }
}
