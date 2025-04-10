<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateMerchantRequest;
use App\Http\Services\Admin\MerchantService;

class MerchantController extends Controller
{
    public function index(MerchantService $merchantService)
    {
        $merchants = $merchantService->getMerchants();
        return view('admin.merchants.index', compact('merchants'));
    }

    public function store(CreateMerchantRequest $request, MerchantService $merchantService)
    {
        $result = $merchantService->createMerchant($request->validated());

        if (! $result['status']) {
            return back()
                ->withErrors(['name' => $result['message']])
                ->withInput();
        }

        return redirect()->route('admin.merchants.index')->with('status', $result['message']);
    }
}
