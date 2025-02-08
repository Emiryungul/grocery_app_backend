<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
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
    $request->validate([
        'address_id' => 'required|exists:addresses,id',
    ]);

    $user = auth()->user();
    $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'message' => 'Your cart is empty.'
        ], 400);
    }

    // Calculate total price
    $totalPrice = $cartItems->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    // Create the order
    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $request->address_id,
        'total_price' => $totalPrice,
        'status' => 'Paid',
    ]);

    // Add items to the order
    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);

        // Deduct stock from the product
        $item->product->decrement('stock', $item->quantity);
    }

    // Clear the user's cart
    Cart::where('user_id', $user->id)->delete();

    return response()->json([
        'message' => 'Order placed successfully.',
        'order' => $order,
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
