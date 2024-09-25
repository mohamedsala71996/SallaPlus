<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreSectionRequest;
use App\Http\Requests\Api\Dashboard\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use App\Models\SectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::with(['items'])->get();
        return SectionResource::collection($sections);
    }

    public function show($id)
    {
        $section = Section::with(['items'])->findOrFail($id);
        return new SectionResource($section);
    }

    public function store(StoreSectionRequest $request)
    {
        // Validate and prepare data for the section
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('sections', 'public');
            $data['photo'] = $photoPath;
        }
        // Create the section
        $section = Section::create($data);
        // Process section items if present
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $index => $item) {
                $itemData = $item;
                // Handle photo upload for section items if present
                if (isset($item['photo']) && $request->hasFile('items.' . $index . '.photo')) {
                    $photoPath = $request->file('items.' . $index . '.photo')->store('sections-items', 'public');
                    $itemData['photo'] = $photoPath;
                }
                $section->items()->create($itemData);
            }
        }
        return response()->json([
            'status' => 'success',
            'section' => new SectionResource($section->load('items'))
        ], 201);
    }

    public function update(UpdateSectionRequest $request, $id)
    {
        $data = $request->validated();
        $section = Section::findOrFail($id);

        if ($request->hasFile('photo')) {
            if ($section->photo) {
                Storage::disk('public')->delete($section->photo);
            }
            $photoPath = $request->file('photo')->store('sections', 'public');
            $data['photo'] = $photoPath;
        }

        $section->update($data);

        // Process section items if present
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $index => $item) {
                $itemData = $item;
                if (isset($item['id'])) {
                    $sectionItem = SectionItem::findOrFail($item['id']);
                    if (isset($item['photo']) && $request->hasFile('items.' . $index . '.photo')) {
                        if ($sectionItem->photo) {
                            Storage::disk('public')->delete($sectionItem->photo);
                        }
                        $photoPath = $request->file('items.' . $index . '.photo')->store('sections-items', 'public');
                        $itemData['photo'] = $photoPath;
                    }
                    $sectionItem->update($itemData);
                } else {
                    if (isset($item['photo']) && $request->hasFile('items.' . $index . '.photo')) {
                        $photoPath = $request->file('items.' . $index . '.photo')->store('sections-items', 'public');
                        $itemData['photo'] = $photoPath;
                    }
                    $section->items()->create($itemData);
                }
            }
        }

        // Remove section items if specified
        if (isset($data['remove_items']) && is_array($data['remove_items'])) {
            foreach ($data['remove_items'] as $itemId) {
                $item = $section->items()->find($itemId);
                if ($item) {
                    if ($item->photo) {
                        Storage::disk('public')->delete($item->photo);
                    }
                    $item->delete();
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'section' => new SectionResource($section->load('items'))
        ], 200);
    }
    public function destroy($id)
    {
        // Find the section or return a 404 response if not found
        $section = Section::findOrFail($id);

        // Delete associated items and their photos
        foreach ($section->items as $item) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $item->delete();
        }

        // Delete the section photo if it exists
        if ($section->photo) {
            Storage::disk('public')->delete($section->photo);
        }

        // Delete the section itself
        $section->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Section and its items deleted successfully',
        ], 200);
    }
}
