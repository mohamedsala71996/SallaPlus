<?php

namespace App\Http\Controllers\Api\Website;

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
        $sections = Section::with(['items'])->orderBy('order')->get();
        return SectionResource::collection($sections);
    }
    public function getSectionByName($sec_name)
    {
          $section = Section::where('name',$sec_name)->with(['items'])->first();
        return new SectionResource($section);
    }
}

