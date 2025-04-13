@extends('layouts.app')

@section('title', 'Merchants')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Merchants</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMerchantModal">
            <i class="bi bi-plus-circle me-1"></i> Add Merchant
        </button>
    </div>

    {{-- Show success / error messages --}}
    <x-alert type="success" :message="session('status')" />
    @if ($errors->any())
        <x-alert type="danger" :message="$errors->first()" />
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Name</th>
                            <th class="border-0">Address</th>
                            <th class="border-0">Created By</th>
                            <th class="border-0">Status</th>
                            <th class="border-0" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($merchants as $merchant)
                            <tr>
                                <td>{{ $merchant->name }}</td>
                                <td>{{ $merchant->address ?? '-' }}</td>
                                <td>{{ $merchant->creator->name ?? 'System' }}</td>
                                <td>
                                    @if ($merchant->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-shop display-6"></i>
                                        <p class="mt-2 mb-0">No merchants found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Merchant Modal --}}
    <div class="modal fade" id="createMerchantModal" tabindex="-1" aria-labelledby="createMerchantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.merchants.store') }}" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createMerchantModalLabel">Add Merchant</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="merchantName" class="form-label">Name</label>
                        <input type="text" name="name" id="merchantName"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="merchantAddress" class="form-label">Address</label>
                        <input type="text" name="address" id="merchantAddress"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Re-open modal if validation error --}}
    @if ($errors->any())
        <script>
            const modal = new bootstrap.Modal(document.getElementById('createMerchantModal'));
            modal.show();
        </script>
    @endif
@endsection
