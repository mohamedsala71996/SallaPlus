<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormRequestResource;
use Illuminate\Http\Request;
use App\Models\FormRequest; // Import the FormRequest model

class FormRequestController extends Controller
{
    public function index()
    {
        // Retrieve all records from the FormRequest model
        $formRequests = FormRequest::all();

        // Return the data using the FormRequestResource with a 200 status code
        return response()->json(FormRequestResource::collection($formRequests), 200);
    }
}
