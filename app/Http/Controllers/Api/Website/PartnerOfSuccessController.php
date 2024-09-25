<?php

namespace App\Http\Controllers\Api\Website;

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

}
