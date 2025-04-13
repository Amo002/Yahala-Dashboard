<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'alpha_dash',
                Rule::unique('roles', 'name')
                    ->where('merchant_id', 1)
                    ->ignore($this->route('role')),
            ],
            'label' => 'required|string|max:255',
        ];
    }
}
