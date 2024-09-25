<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    public function storeSeveralCategories(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.name' => 'required|string|max:255|unique:categories,name',
        ]);

        $categories = [];

        foreach ($request->categories as $categoryData) {
            $categories[] = Category::create($categoryData);
        }

        return response()->json($categories, 201);
    }

    public function updateSeveralCategories(Request $request)
    {
        // Validate request data
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'sometimes|exists:categories,id',
            'categories.*.name' => 'required|string|max:255',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:categories,id'
        ]);

        $categories = [];
        $categoryIdsToRemove = $request->input('remove_items', []);

        // Process category updates and creations
        foreach ($request->input('categories', []) as $categoryData) {
            if (isset($categoryData['id'])) {
                // Update existing category
                $category = Category::find($categoryData['id']);
                if ($category) {
                    // Check for unique constraint manually
                    if (Category::where('name', $categoryData['name'])->where('id', '!=', $category->id)->exists()) {
                        return response()->json(['error' => "The name '{$categoryData['name']}' has already been taken."], 422);
                    }
                    $category->update($categoryData);
                    $categories[] = $category;
                }
            } else {
                // Create new category
                if (Category::where('name', $categoryData['name'])->exists()) {
                    return response()->json(['error' => "The name '{$categoryData['name']}' has already been taken."], 422);
                }
                $categories[] = Category::create($categoryData);
            }
        }

        // Delete categories
        if (!empty($categoryIdsToRemove)) {
            Category::whereIn('id', $categoryIdsToRemove)->delete();
        }

        return response()->json($categories, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->update($request->all());

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
