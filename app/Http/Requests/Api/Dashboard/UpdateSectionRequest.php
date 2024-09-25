<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust as necessary for authorization
    }

    public function rules()
    {
        $sectionId = $this->route('section'); // Assuming 'section' is the route parameter name for the section ID

        return [
            'name' => 'required|string|max:255|unique:sections,name,'.$this->id,
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|integer|exists:section_items,id', // Ensure item exists if an ID is provided
            'items.*.title' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'items.*.icon' => 'nullable|string',
            'items.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'items.*.order' => 'nullable|integer',
            'remove_items' => 'nullable|array',
            'remove_items.*' => 'integer|exists:section_items,id' // Ensure items to remove exist
        ];
    }
}
