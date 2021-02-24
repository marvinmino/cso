<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResource extends JsonResource
{

/**
    * Transform the resource into an array.
    *
    *  @param  \Illuminate\Http\Request  $request
    *  @return array
    **/
    public function toArray($request)
    {
        return [
            'id'             =>    $this->id,
            'username'       =>    $this->username,
            'token'          =>    $this->token,
            'access_token'   =>    $this->access_token
        ];
    }
}
