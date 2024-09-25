<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $categories = Category::with('features')->get();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:features,name',
            'category_id' => 'required|exists:categories,id',
            'basic_package' => 'required|boolean',
            'advanced_package' => 'required|boolean',
            'professional_package' => 'required|boolean',
        ]);

        $feature = Feature::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Feature created successfully',
            'data' => $feature,
        ], 201);

    }

    public function storeSeveralFeatures(Request $request)
    {
        $data = $request->validate([
            'features' => 'required|array',
            'features.*.name' => 'required|string|max:255|unique:features,name',
            'features.*.basic_package' => 'required|boolean',
            'features.*.advanced_package' => 'required|boolean',
            'features.*.professional_package' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $features = [];

        foreach ($data['features'] as $featureData) {
            $featureData['category_id'] = $data['category_id'];
            $features[] = Feature::create($featureData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Features created successfully',
            'data' => $features,
        ], 201);
    }

    public function updateSeveralFeatures(Request $request)
    {
        $data = $request->validate([
            'features' => 'required|array',
            'features.*.id' => 'sometimes|exists:features,id',
            'features.*.name' => 'required|string|max:255',
            'features.*.basic_package' => 'required|boolean',
            'features.*.advanced_package' => 'required|boolean',
            'features.*.professional_package' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:features,id'
        ]);

        $features = [];
        $featureIdsToRemove = $request->input('remove_items', []);

        foreach ($request->input('features', []) as $featureData) {
            if (isset($featureData['id'])) {
                $feature = Feature::find($featureData['id']);
                if ($feature) {
                    if (Feature::where('name', $featureData['name'])->where('id', '!=', $feature->id)->exists()) {
                        return response()->json(['error' => "The name '{$featureData['name']}' has already been taken."], 422);
                    }
                    $featureData['category_id'] = $data['category_id'];
                    $feature->update($featureData);
                    $features[] = $feature;
                }
            } else {
                if (Feature::where('name', $featureData['name'])->exists()) {
                    return response()->json(['error' => "The name '{$featureData['name']}' has already been taken."], 422);
                }
                $featureData['category_id'] = $data['category_id'];
                $features[] = Feature::create($featureData);
            }
        }

        if (!empty($featureIdsToRemove)) {
            Feature::whereIn('id', $featureIdsToRemove)->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Features updated successfully',
            'data' => $features,
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:features,name,' . $id,
            'category_id' => 'required|exists:categories,id',
            'basic_package' => 'required|boolean',
            'advanced_package' => 'required|boolean',
            'professional_package' => 'required|boolean',
        ]);

        $feature = Feature::find($id);

        if (!$feature) {
            return response()->json(['message' => 'Feature not found'], 404);
        }

        $feature->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Feature updated successfully',
            'data' => $feature,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $feature = Feature::find($id);

        if (!$feature) {
            return response()->json(['message' => 'Feature not found'], 404);
        }

        $feature->delete();

        return response()->json(['message' => 'Feature deleted successfully']);
    }
}
