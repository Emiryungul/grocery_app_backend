<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Fetch all products
    public function index()
    {
        // Retrieve all products
        $products = Product::all();

        // Return the response in JSON format
        return response()->json([
            'data' => $products
        ], 200);
    }
}
