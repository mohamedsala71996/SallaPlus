<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'category_name' => trim($this->name), // Assuming 'name' is the field for category_name
            'features' => FeatureResource::collection($this->whenLoaded('features')),
        ];
    }
}
