<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'=>$this->title,
            'content'=>$this->content,
            'images'=>$this->gallery !=null ? ImageDisplayResource::collection($this->gallery->images) :null,
            'hash' =>$this->hash

        ];
    }
}
