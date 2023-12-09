<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $products = auth()->user()->cart->products;

        if($products->isEmpty()){
            return response([
                'message' => 'No products found'
            ]);
        }

        return $products;
    }

    public function store(Product $product, Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart;

        $cart->products()->attach($product->id, $request->all());

        return response()->json([
            'message' => 'Product stored in the cart successfully'
        ], 200);
    }

    public function update(Product $product, Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart;

        if (!$cart->products->contains($product->id)) {
            return response()->json([
                'message' => 'No such product id in the cart'
            ], 422);
        }

        $cart->products()->updateExistingPivot($product->id, ['quantity' => $request->input('quantity')]);

        return response()->json([
            'message' => 'Product updated successfully'
        ], 200);
    }

    public function deleteAllProducts(){
        auth()->user()->cart->products()->detach();

        return response([
            'message' => 'All products deleted successfully'
        ]);
    }
}
