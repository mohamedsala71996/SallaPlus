<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
            'feature_name' => $this->name,
            'basic_package' => $this->basic_package,
            'advanced_package' => $this->advanced_package,
            'professional_package' => $this->professional_package,
            // 'category_id' => $this->category_id,
        ];
    }
}
