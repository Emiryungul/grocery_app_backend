<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        // Fetch all categories with their related products
        $categories = Category::with('products')->get();

        // Return the response in the desired format
        return response()->json([
            'data' => $categories
        ], 200);
    }
    
    public function getProductsByCategory($id)
    {
        // Find the category by ID and load related products
        $category = Category::with('products')->find($id);

        // If the category is not found, return a 404 response
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Return category and products under the 'data' key
        return response()->json([
            'data' => [
                'products' => $category->products,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at
                ],
            ]
        ], 200);
    }

    // Show a single category with its products
    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category, 200);
    }

    // Create a new category
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
 
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->update($request->all());
        return response()->json($category, 200);
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted'], 200);
    }
}
