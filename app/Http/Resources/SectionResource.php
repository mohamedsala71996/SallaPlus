<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,

        ];
        // Conditionally add fields if they are not null
        if (!is_null($this->title)) {
            $data['title'] = $this->title;
        }

        if (!is_null($this->description)) {
            $data['description'] = $this->description;
        }

        if (!is_null($this->photo)) {
            $data['photo'] = Storage::url($this->photo);
        }

        if (!is_null($this->order)) {
            $data['order'] = $this->order;
        }

        $data['items'] = SectionItemResource::collection($this->whenLoaded('items'));
        return $data;

    }
}
