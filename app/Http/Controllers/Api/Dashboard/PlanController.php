<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return response()->json($plans, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:plans,name',
            'price' => 'required|numeric',
            // 'category_id' => 'nullable|exists:categories,id',
        ]);

        $plan = Plan::create($request->all());

        return response()->json($plan, 201);
    }

    public function storeSeveralPlans(Request $request)
    {
       $data= $request->validate([
            'plans' => 'required|array',
            'plans.*.name' => 'required|string|max:255|unique:plans,name',
            'plans.*.price' => 'required|numeric',
            // 'plans.*.category_id' => 'nullable|exists:categories,id',
            // 'category_id' => 'nullable|exists:categories,id',
        ]);

        $plans = [];

        foreach ($data['plans'] as $planData) {
            // $planData['category_id']=$data['category_id'];
            $plans[] = Plan::create($planData);
        }

        return response()->json($plans, 201);
    }

    public function updateSeveralPlans(Request $request)
    {
        // Validate request data
        $data=  $request->validate([
            'plans' => 'required|array',
            'plans.*.id' => 'sometimes|exists:plans,id',
            'plans.*.name' => 'required|string|max:255',
            'plans.*.price' => 'required|numeric',
            // 'category_id' => 'nullable|exists:categories,id',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:plans,id'
        ]);

        $plans = [];
        $planIdsToRemove = $request->input('remove_items', []);

        // Process plan updates and creations
        foreach ($request->input('plans', []) as $planData) {
            if (isset($planData['id'])) {
                // Update existing plan
                $plan = Plan::find($planData['id']);
                if ($plan) {
                    // Check for unique constraint manually
                    if (Plan::where('name', $planData['name'])->where('id', '!=', $plan->id)->exists()) {
                        return response()->json(['error' => "The name '{$planData['name']}' has already been taken."], 422);
                    }
                    // $planData['category_id']=$data['category_id'];
                    $plan->update($planData);
                    $plans[] = $plan;
                }
            } else {
                // Create new plan
                if (Plan::where('name', $planData['name'])->exists()) {
                    return response()->json(['error' => "The name '{$planData['name']}' has already been taken."], 422);
                }
                // $planData['category_id']=$data['category_id'];

                $plans[] = Plan::create($planData);
            }
        }

        // Delete plans
        if (!empty($planIdsToRemove)) {
            Plan::whereIn('id', $planIdsToRemove)->delete();
        }

        return response()->json($plans, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:plans,name,'.$id,
            'price' => 'required|numeric',
            // 'category_id' => 'nullable|exists:categories,id',
        ]);

        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $plan->update($request->all());

        return response()->json($plan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully']);
    }

}
