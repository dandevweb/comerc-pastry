<?php

namespace App\Http\Resources;

use App\Enums\ProductTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id ?? null,
            'name' => $this->name ?? null,
            'type' => $this->when(isset($this->type), [
                'value'       => $this->type->value ?? null,
                'name'        => $this->type->name ?? null,
                'description' => $this->type ?? null ? ProductTypeEnum::getDescription($this->type->value ?? null) : null,
            ]),
            'price'      => $this->when($this->price ?? null, $this->price ?? 0),
            'photo'      => $this->when($this->photo ?? null, $this->photo ?? null),
            'photo_path' => $this->when($this->photo ?? null, $this->photoPath ?? null),
        ];
    }
}
