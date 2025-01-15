<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Address;
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

    // Find the product by its ID
    $product = Product::find($request->product_id);

    // Check if the product has stock available
    if ($product->stock <= 0) {
        return response()->json([
            'message' => 'This product is out of stock and cannot be added to the cart.',
        ], 400);
    }

    // Add the product to the cart or update its quantity
    $cartItem = Cart::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ],
        [
            'quantity' => \DB::raw('quantity + 1'), // Increment quantity by 1
        ]
    );

    return response()->json([
        'message' => 'Product added to cart successfully.',
        'cart_item' => $cartItem,
    ], 200);
}



public function payForTheCart(Request $request)
{
    $user = auth()->user();
    $addressId = $request->address_id;

    // Retrieve all cart items for the user
    $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

    // Check stock availability
    foreach ($cartItems as $item) {
        if ($item->quantity > $item->product->stock) {
            return response()->json([
                'message' => 'Insufficient stock for product: ' . $item->product->name
            ], 400);
        }
    }

    // Deduct stock and clear the cart
    foreach ($cartItems as $item) {
        $item->product->decrement('stock', $item->quantity); // Reduce stock
    }

    // Delete all cart items
    Cart::where('user_id', $user->id)->delete();

    return response()->json([
        'message' => 'Order processed successfully, and stock updated.'
    ], 200);
}


public function deleteCartItem(Request $request, $cartItemId)
{
    // Retrieve the cart item and ensure it belongs to the authenticated user
    $cartItem = Cart::where('id', $cartItemId)
        ->where('user_id', auth()->id())
        ->first();

    // Check if the cart item exists
    if (!$cartItem) {
        return response()->json([
            'message' => 'Cart item not found or does not belong to the user.'
        ], 404);
    }

    // Decrease quantity by 1
    if ($cartItem->quantity > 1) {
        $cartItem->decrement('quantity');
    } else {
        // If quantity is 1, delete the item
        $cartItem->delete();
    }

    return response()->json([
        'message' => 'Cart item updated successfully.'
    ], 200);
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
