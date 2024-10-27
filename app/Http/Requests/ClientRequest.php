<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($this->route('client'))
            ],
            'phone'        => ['required', 'string', 'max:20'],
            'address'      => ['required', 'string', 'max:255'],
            'number'       => ['required', 'string', 'max:255'],
            'complement'   => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:100'],
            'city'         => ['required', 'string', 'max:255'],
            'state'        => ['required', 'string', 'max:2'],
            'birth_date'   => ['required', 'date'],
            'zip_code'     => ['required', 'string', 'max:10'],
        ];
    }
}
