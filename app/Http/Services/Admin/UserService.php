<?php

namespace App\Http\Services\Admin;

use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class UserService
{
    public function getUsersFiltered(?int $merchantId = null)
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(auth()->user()->merchant_id);

        $query = User::with(['roles:id,name,label', 'merchant:id,name', 'inviter:id,name']);

        if ($merchantId) {
            // If merchant is selected, get ONLY that merchant’s users
            $query->where('merchant_id', $merchantId);
        } else {
            // Otherwise, show only system users (merchant_id = 1)
            $query->where('merchant_id', 1);
        }

        return $query->get();
    }
}
