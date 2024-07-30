<?php

namespace App\Http\Resources;

use App\Traits\LoggerTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\LogsTrait;
use Carbon\Carbon;

class LogsResource extends JsonResource
{
    use LogsTrait,LoggerTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $this->user ? json_decode(json_encode(new UserResource($this->user))) : null;
        return [
            'id' => $this->id,
            'user' => $user ? [
                'username' => $user->username,
                'email' => $user->email,
                'full_name' => $user->profile?->full_name,
            ] : null,
            'actor' => $this->actor,
            'action' => $this->action,
            'type' => $this->map_action_types($this->type),
            'level' => $this->map_level($this->level),
            'module' => $this->module,
            'old_data' => json_decode($this->old_data ?? ''),
            'new_data' => json_decode($this->new_data ?? ''),
            'date' => $this->created_at->toDayDateTimeString(),
        ];
    }
}