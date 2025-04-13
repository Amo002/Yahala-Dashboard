<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ids = auth()->user()
            ->roles()
            ->wherePivot('merchant_id', 1)
            ->with('permissions:id')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('id')
            ->unique();

        return [
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => $ids->isNotEmpty()
                ? ['required', 'integer', 'in:' . $ids->implode(',')]
                : ['nullable'], // fallback if user has no permissions
        ];
    }
}
