<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id ?? null,
            'name' => $this->name ?? null,
            'type' => isset($this->type) ? [
                'value' => $this->type->value ?? null,
                'name'  => $this->type->name ?? null,
            ] : null,
            'price' => $this->price ?? null,
            'photo' => $this->photo ?? null,
        ];
    }
}
