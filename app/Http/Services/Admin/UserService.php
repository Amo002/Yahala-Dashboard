<?php

namespace App\Http\Services\Admin;

use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class UserService
{


    public function getUsersFiltered(?string $teamName = null, ?int $merchantId = null)
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(auth()->user()->team_id);

        $query = User::with(['roles:id,name,label', 'team:id,name', 'merchant:id,name']);

        if ($teamName) {
            $query->whereHas('team', fn($q) => $q->where('name', $teamName));
        }

        if ($merchantId) {
            $query->where('merchant_id', $merchantId);
        }

        return $query->get();
    }
}
