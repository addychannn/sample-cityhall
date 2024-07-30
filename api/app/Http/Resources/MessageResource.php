<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MessageResource extends JsonResource
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
            'firstname' =>$this->firstname,
            'lastname' => $this->lastname,
            'deleted_at'=>$this->deleted_at,
            'contact' => $this->contact,
            'email' => $this->email,
            'message' => $this->message,
            'created_at'=> Carbon::parse($this->created_at,'GMT')->toDayDateTimeString(),
           
        ];
    }
}
