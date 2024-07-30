<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QuitterResource extends JsonResource
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
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'location' =>  $this->location,
            'barangay' => $this->barangays?->name,
            'additional_notes' => $this->additional_notes,
            'preferred_date' => Carbon::parse($this->preferred_date,'GMT')->toDayDateTimeString(),
            'status' => $this->status,
            'admin_remarks' => $this->admin_remarks,
            'reschedule_count' => $this->reschedule_count
        ];
    }
}
