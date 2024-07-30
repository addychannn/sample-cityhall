<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'hash' => $this->hash,
            'event_title' =>$this->event_title,
            'description' => $this->description,
            'start_date' => Carbon::parse($this->start_date,'GMT')->toDayDateTimeString(),
            'end_date' =>Carbon::parse($this->end_date,'GMT')->toDayDateTimeString(),
            'image' => $this->gallery ? ImageDisplayResource::collection($this->gallery->images): null

        ];
    }
}
