<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PartnerOfSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerOfSuccessController extends Controller
{
    public function index()
    {
        $partners = PartnerOfSuccess::all();
        return response()->json($partners, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'link' => 'required|url|max:255',
        ]);

        // Handle the file upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('partner_photos', 'public');
        }

        $partner = PartnerOfSuccess::create([
            'photo' => $photoPath ?? null,
            'link' => $request->input('link'),
        ]);
        return response()->json([
            'status' => 'success',
            'partner' => $partner
        ], 201);

    }

    public function storeSeveralPartners(Request $request)
    {
        $data = $request->validate([
            'partners' => 'required|array',
            'partners.*.photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'partners.*.link' => 'required|url|max:255',
        ]);

        $partners = [];

        foreach ($data['partners'] as $partnerData) {
            if (isset($partnerData['photo'])) {
                $photoPath = $partnerData['photo']->store('partner_photos', 'public');
            }

            $partners[] = PartnerOfSuccess::create([
                'photo' => $photoPath ?? null,
                'link' => $partnerData['link'],
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Partners created successfully',
            'data' => $partners,
        ], 201);
    }

    public function updateSeveralPartners(Request $request)
    {
        $data = $request->validate([
            'partners' => 'required|array',
            'partners.*.id' => 'sometimes|exists:partners_of_success,id',
            'partners.*.photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp',
            'partners.*.link' => 'required|url|max:255',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:partners_of_success,id'
        ]);

        $partners = [];
        $partnerIdsToRemove = $request->input('remove_items', []);

        foreach ($data['partners'] as $partnerData) {
            if (isset($partnerData['id'])) {
                $partner = PartnerOfSuccess::find($partnerData['id']);
                if ($partner) {
                    if (isset($partnerData['photo'])) {
                        // Handle file upload
                        $photoPath = $partnerData['photo']->store('partner_photos', 'public');
                        // Delete old photo if exists
                        if ($partner->photo) {
                            Storage::disk('public')->delete($partner->photo);
                        }
                        $partnerData['photo'] = $photoPath;
                    }

                    $partner->update($partnerData);
                    $partners[] = $partner;
                }
            } else {
                if (isset($partnerData['photo'])) {
                    $photoPath = $partnerData['photo']->store('partner_photos', 'public');
                }

                $partners[] = PartnerOfSuccess::create([
                    'photo' => $photoPath ?? null,
                    'link' => $partnerData['link'],
                ]);
            }
        }

        if (!empty($partnerIdsToRemove)) {
            $partnersToRemove = PartnerOfSuccess::whereIn('id', $partnerIdsToRemove)->get();
            foreach ($partnersToRemove as $partner) {
                if ($partner->photo) {
                    Storage::disk('public')->delete($partner->photo);
                }
                $partner->delete();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Partners updated successfully',
            'data' => $partners,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp',
            'link' => 'required|url|max:255',
        ]);

        $partner = PartnerOfSuccess::find($id);

        if (!$partner) {
            return response()->json(['message' => 'Partner not found'], 404);
        }

        if ($request->hasFile('photo')) {
            // Handle file upload
            $photoPath = $request->file('photo')->store('partner_photos', 'public');
            // Delete old photo if exists
            if ($partner->photo) {
                Storage::disk('public')->delete($partner->photo);
            }
            $partner->photo = $photoPath;
        }

        $partner->link = $request->input('link');
        $partner->save();

        return response()->json([
            'status' => 'success',
            'partner' => $partner
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $partner = PartnerOfSuccess::find($id);

        if (!$partner) {
            return response()->json(['message' => 'Partner not found'], 404);
        }

        // Delete photo if exists
        if ($partner->photo) {
            Storage::disk('public')->delete($partner->photo);
        }

        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully']);
    }
}
