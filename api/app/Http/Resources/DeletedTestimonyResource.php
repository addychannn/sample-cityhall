<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeletedTestimonyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'=> $this->title,
            'body'=> $this->body,
            'date' => $this->created_at->toFormattedDateString(),
            'image' => $this->gallery ? ImageDisplayResource::collection($this->gallery->images): null
        ];
    }
}
