<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->hash,
            'email' => $this->email,
            'username' => $this->username,
            'active' => $this->disabled_at ? false : true,
            'is_verified' => $this->email_verified_at ? true : false,

            'profile' => new ProfilesResource($this->profile),

            'roles' => $this->roles->map->only(['id', 'name']),
            'direct_permissions' => $this->getDirectPermissions()->pluck(['id']),
        ];
    }
}
