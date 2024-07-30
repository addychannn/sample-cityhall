<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DeletedArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *s
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'hash' => $this->hash,
            'articleTitle' => $this->articleTitle,
            'description' => $this->description,
            'created_at'=> $this->created_at->toFormattedDateString(),
            'image' => $this->gallery ? ImageDisplayResource::collection($this->gallery->images): null
        ];
    }
}
