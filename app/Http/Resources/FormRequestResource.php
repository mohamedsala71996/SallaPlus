<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'business_email' => $this->business_email,
            'phone_number' => $this->phone_number,
            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'average_online_orders' => $this->average_online_orders,
            'has_store' => $this->has_store,
            'hear_about' => json_decode($this->hear_about, true), // Decoding the JSON field
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
