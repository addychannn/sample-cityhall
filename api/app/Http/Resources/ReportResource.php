<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ReportResource extends JsonResource
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
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'violator_name' => $this->violator_name,
            'violator_location' => $this->violator_location,
            'barangay' => $this->barangays?->name,
            'violation_date' =>Carbon::parse($this->violation_date,'GMT')->toFormattedDayDateString(),
            'landmark' => $this->landmark,
            'violation_description' => $this->violation_description,
            'created_at'=> $this->created_at->toDayDateTimeString(),
            'admin_remarks' => $this->admin_remarks,
            'image' => $this->gallery ? ImageDisplayResource::collection($this->gallery->images): null,
            'status' => $this->status
        ];
    }
}
