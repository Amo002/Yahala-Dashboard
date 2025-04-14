<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class AssignUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validIds = Role::where('merchant_id', 1)->pluck('id')->toArray();

        return [
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'in:' . implode(',', $validIds)],
        ];
    }

    public function messages(): array
    {
        return [
            'role_ids.*.in' => 'One or more selected roles are invalid.',
        ];
    }
}
