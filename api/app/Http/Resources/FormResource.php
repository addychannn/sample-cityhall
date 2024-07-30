<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->hash,
            'alt' =>$this->alt,
            'name' => $this->path,
            'mime'=> $this->mime,
            'extension'=>$this->extension,
            'size' => $this->size

        ];
    }
}
