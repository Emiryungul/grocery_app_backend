<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Product;
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
    // Retrieve favorite products for the authenticated user and eager load product details
    $favorites = Favorite::where('user_id', auth()->id())
        ->with('product') // Eager load related product data
        ->get()
        ->map(function ($favorite) {
            // Format each favorite item with selected product details
            return [
                'id' => $favorite->id,
                'product_id' => $favorite->product->id,
                'user_id' => $favorite->user_id,
                'created_at' => $favorite->created_at,
                'updated_at' => $favorite->updated_at,
                'product' => [
                    'id' => $favorite->product->id,
                    'name' => $favorite->product->name,
                    'description' => $favorite->product->description,
                    'price' => $favorite->product->price,
                    'image_url' => $favorite->product->image_url,
                    'feature' => $favorite->product->feature,
                    'expiration' => $favorite->product->expiration,
                    'energy' => $favorite->product->energy,
                ],
            ];
        });

    // Return response with structured data
    return response()->json([
        'message' => 'Favorites retrieved successfully.',
        'favorites' => $favorites,
    ], 200);
}

}
