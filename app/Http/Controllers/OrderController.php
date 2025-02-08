<?php


namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderHistory()
{
    $orders = Order::where('user_id', auth()->id())
        ->with('items.product')
        ->orderBy('created_at', 'desc') // Ensure recent orders are included
        ->get();

    return response()->json([
        'message' => 'Order history retrieved successfully.',
        'orders' => $orders,
    ], 200);
}

}
