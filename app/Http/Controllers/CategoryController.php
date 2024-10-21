<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        // Fetch all categories with related products
        $categories = Category::with('products')->get();

        // Add the image URL to each category
        $categoriesWithImages = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image_url' => $category->image_url, // Include the image URL
                'products' => $category->products,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });

        // Return the modified response
        return response()->json(['data' => $categoriesWithImages], 200);
    }

    // Get products by category ID
    public function getProductsByCategory($id)
    {
        // Find the category by ID with products
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Return category with products and image URL
        return response()->json([
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'image_url' => $category->image_url, // Include the image URL
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ],
                'products' => $category->products,
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

        // Include the image URL in the response
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'image_url' => $category->image_url, // Include the image URL
            'products' => $category->products,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ], 200);
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
