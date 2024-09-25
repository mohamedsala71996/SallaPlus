<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OurServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo ? Storage::url($this->photo) : null, // Full URL to the photo
            'price' => $this->price,
            // 'created_at' => $this->created_at->toDateTimeString(),
            // 'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
