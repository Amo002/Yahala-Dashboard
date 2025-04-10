<?php

namespace App\Http\Services\Admin;

use App\Models\Merchant;

class MerchantService
{
    public function getMerchants()
    {
        return Merchant::where('id', '!=', 1)
            ->with('creator:id,name')
            ->latest()
            ->get();
    }

    public function createMerchant(array $data): array
    {
        // Prevent duplicate names
        if (Merchant::where('name', $data['name'])->exists()) {
            return [
                'status' => false,
                'message' => 'A merchant with this name already exists.',
            ];
        }

        Merchant::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'created_by' => auth()->id(),
        ]);

        return [
            'status' => true,
            'message' => 'Merchant created successfully.',
        ];
    }
}
