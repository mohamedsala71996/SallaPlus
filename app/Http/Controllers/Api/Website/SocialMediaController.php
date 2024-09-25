<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\SocialMediaRequest;
use App\Models\Admin;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{

    public function index()
    {
        $socialMedia = SocialMedia::all(); // Retrieve all social media records

        return response()->json($socialMedia, 200); // Return with 200 OK status
    }

}
