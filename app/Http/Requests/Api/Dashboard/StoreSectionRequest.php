<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust as necessary for authorization
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:sections,name',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'order' => 'nullable|integer',
            'items' => 'nullable|array',
            'items.*.title' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'items.*.icon' => 'nullable|string',
            'items.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'items.*.order' => 'nullable|integer',
            'items.*.link' => 'nullable|url|max:255', // Validate link field
            'items.*.statistics' => 'nullable|string|max:255', // Validate link field

        ];
    }
}
