<?php
namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreOurServiceRequest;
use App\Http\Requests\Api\Dashboard\StoreSeveralServicesRequest;
use App\Http\Requests\Api\Dashboard\UpdateOurServiceRequest;
use App\Http\Requests\Api\Dashboard\UpdateSeveralServicesRequest;
use App\Http\Resources\OurServiceResource;
use App\Models\OurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OurServiceController extends Controller
{
    public function index()
    {
        $services = OurService::all();
        return response()->json([
            'count' => $services->count(),
            'services' => OurServiceResource::collection($services),
        ], 200); // 200 status code for a successful GET request
    }

    public function store(StoreOurServiceRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('our-services', 'public');
            $data['photo'] = $photoPath;
        }

        $service = OurService::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Service created successfully',
            'service' => new OurServiceResource($service),
        ], 201); // 201 status code for a successful resource creation
    }

    public function createSeveralServices(StoreSeveralServicesRequest $request)
    {
        $servicesData = $request->services;
        $services = [];

        foreach ($servicesData as $serviceData) {
            // Handle file upload
            if (isset($serviceData['photo']) && $serviceData['photo']) {
                $photoPath = $serviceData['photo']->store('our-services', 'public');
                $serviceData['photo'] = $photoPath;
            }

            // Create the service
            $service = OurService::create($serviceData);
            $services[] = new OurServiceResource($service);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Services created successfully',
            'services' => $services,
        ], 201); // 201 status code for a successful resource creation
    }
    // public function show($id)
    // {
    //     $service = OurService::find($id);
    //     if (!$service) {
    //         return response()->json(['message' => 'Service not found'], 404);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'service' => new OurServiceResource($service),
    //     ], 200); // 200 status code for a successful GET request
    // }

    public function update(UpdateOurServiceRequest $request, $id)
    {
        $service = OurService::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($service->photo) {
                Storage::disk('public')->delete($service->photo);
            }

            $photoPath = $request->file('photo')->store('our-services', 'public');
            $data['photo'] = $photoPath;
        }

        $service->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Service updated successfully',
            'service' => new OurServiceResource($service),
        ], 200); // 200 status code for a successful update
    }

    public function updateSeveralServices(UpdateSeveralServicesRequest $request)
    {
        $data = $request->validated();
        $services = [];
        $serviceIdsToRemove = $request->input('remove_items', []);

        foreach ($data['services'] as $serviceData) {
            if (isset($serviceData['id'])) {
                $service = OurService::find($serviceData['id']);
                if ($service) {
                    if (isset($serviceData['photo'])) {
                        $photoPath = $serviceData['photo']->store('our-services', 'public');
                        if ($service->photo) {
                            Storage::disk('public')->delete($service->photo);
                        }
                        $serviceData['photo'] = $photoPath;
                    }
                    $service->update($serviceData);
                    $services[] = new OurServiceResource($service);
                }
            } else {
                if (isset($serviceData['photo'])) {
                    $photoPath = $serviceData['photo']->store('our-services', 'public');
                }
                $services[] = new OurServiceResource(OurService::create([
                    'photo' => $photoPath ?? null,
                    'name' => $serviceData['name'],
                    'price' => $serviceData['price'],
                ]));
            }
        }
        if (!empty($serviceIdsToRemove)) {
            $servicesToRemove = OurService::whereIn('id', $serviceIdsToRemove)->get();
            foreach ($servicesToRemove as $service) {
                if ($service->photo) {
                    Storage::disk('public')->delete($service->photo);
                }
                $service->delete();
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Services updated successfully',
            'services' => $services,
        ], 200);
    }

    public function destroy($id)
    {
        $service = OurService::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        if ($service->photo) {
            Storage::disk('public')->delete($service->photo);
        }

        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Service deleted successfully',
        ], 200); // 200 status code for a successful deletion
    }
}
