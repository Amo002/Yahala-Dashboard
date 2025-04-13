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
        $validIds = Role::where('merchant_id', 1)->pluck('id')->implode(',');

        return [
            'role_id' => ['required', 'integer', 'in:' . $validIds],
        ];
    }
}
