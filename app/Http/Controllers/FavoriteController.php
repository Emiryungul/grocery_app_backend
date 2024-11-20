<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ]
        );

        return response()->json([
            'message' => 'Product added to favorites.',
            'favorite' => $favorite,
        ], 201);
    }
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('product') // Eager load related product data
            ->get();

        return response()->json([
            'message' => 'Favorites retrieved successfully.',
            'favorites' => $favorites,
        ], 200);
    }
}
