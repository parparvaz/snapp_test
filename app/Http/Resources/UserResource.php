<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'mobile_number' => $this->mobile_number,
            'password' => $this->password,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'transactions' => TransactionResource::collection($this->when($this['transactions'] != null,$this['transactions'] ))
        ];
    }
}
