<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'  => ['required', 'exists:clients,id'],
            'products'   => ['required', 'array'],
            'products.*' => ['required', 'exists:products,id'],
        ];
    }
}
