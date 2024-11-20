<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class CartController extends Controller
{
    // POST: Add product to cart
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
    ]);

    $user = $request->user(); // Get authenticated user
    $product_id = $request->product_id;

    // Check if the product is already in the cart for this user
    $cartItem = Cart::where('user_id', $user->id)->where('product_id', $product_id)->first();

    if ($cartItem) {
        // If it exists, increase the quantity
        $cartItem->increment('quantity');
    } else {
        // If it doesn't exist, create a new entry
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
            'quantity' => 1,
        ]);
    }

    // Calculate the total price for the user's cart
    $totalPrice = Cart::where('user_id', $user->id)
        ->join('products', 'cart.product_id', '=', 'products.id')
        ->sum(DB::raw('cart.quantity * products.price'));

    return response()->json([
        'message' => 'Product added to cart successfully',
        'meta' => [
            'totalPrice' => $totalPrice,
        ],
    ], 201);
}


    
public function index(Request $request)
{
    $user = $request->user(); // Get authenticated user

    $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

    $data = $cartItems->map(function ($item) {
        return [
            'product_id' => $item->product->id,
            'name' => $item->product->name,
            'image_url' => $item->product->image_url,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
            'total_price' => $item->quantity * $item->product->price,
        ];
    });

    // Calculate the total price
    $totalPrice = $cartItems->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    return response()->json([
        'data' => $data,
        'meta' => [
            'totalPrice' => $totalPrice,
            'message' => 'Cart items retrieved successfully',
        ],
    ], 200);
}



}
