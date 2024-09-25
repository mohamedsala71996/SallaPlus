<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormRequest; // Import the FormRequest model

class FormRequestController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'average_online_orders' => 'required|string|max:255',
            'has_store' => 'required|boolean',
            'hear_about' => 'required|array', // Validate as array since it's a checkbox
            'hear_about.*' => 'string|max:255', // Validate each item in the array
        ]);

        // Convert the hear_about array to a JSON string
        $data['hear_about'] = json_encode($data['hear_about']);

        // Create a new form request entry in the database
        $formRequest = FormRequest::create($data);

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Form request submitted successfully',
            'data' => $formRequest,
        ], 201);
    }
}
