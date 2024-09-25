<?php
namespace App\Http\Controllers\Api\Website;

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

}
