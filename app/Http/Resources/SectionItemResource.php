<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SectionItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Initialize the base data array with id and section_id
        $data = [
            'id' => $this->id,
            'section_id' => $this->section_id,
        ];

        // Conditionally add fields if they are not null
        if (!is_null($this->title)) {
            $data['title'] = $this->title;
        }

        if (!is_null($this->description)) {
            $data['description'] = $this->description;
        }

        if (!is_null($this->link)) {
            $data['link'] = $this->link;
        }

        if (!is_null($this->statistics)) {
            $data['statistics'] = $this->statistics;
        }

        if (!is_null($this->icon)) {
            $data['icon'] = $this->icon;
        }

        if (!is_null($this->photo)) {
            $data['photo'] = Storage::url($this->photo);
        }

        if (!is_null($this->order)) {
            $data['order'] = $this->order;
        }

        return $data;
    }
}
