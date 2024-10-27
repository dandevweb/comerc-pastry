<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id ?? null,
            'total'      => $this->total ?? null,
            'created_at' => $this->created_at ?? null,
            'client'     => isset($this->client) ? new ClientResource($this->client) : [],
            'products'   => isset($this->products) ? ProductResource::collection($this->products) : [],
        ];
    }
}
