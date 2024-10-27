<?php

namespace App\Http\Requests;

use App\Enums\ProductTypeEnum;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'type'  => ['required', Rule::in(ProductTypeEnum::values())],
            'price' => ['required', 'numeric'],
            'photo' => ['required', 'image', 'max:1024'],
        ];
    }
}
