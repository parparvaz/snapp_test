<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SwapResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'amount' => $this->amount,
            'fee' => $this->fee,
            'source_id' => $this->source_id,
            'destination_id' => $this->destination_id,
            'status' => $this->status,
            'uuid' => $this->uuid
        ];
    }
}
