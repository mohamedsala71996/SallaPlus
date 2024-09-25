<?php

namespace App\Http\Requests\Api\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Determine if the user is authenticated
        $isAuthenticated = $this->user() !== null;

        return [
            'service_id' => [
                'required',
                'exists:our_services,id', // Ensure the service exists in the 'our_services' table
            ],
            // If the user is not authenticated, validate 'cart_token'
            // 'cart_token' => $isAuthenticated ? 'nullable' : 'required|string',
        ];
    }
}
