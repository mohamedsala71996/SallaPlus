<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeveralServicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'services' => 'required|array',
            'services.*.id' => 'sometimes|exists:our_services,id',
            'services.*.name' => 'sometimes|string|max:255',
            'services.*.price' => 'sometimes|numeric',
            'services.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:our_services,id',
        ];
    }
}
