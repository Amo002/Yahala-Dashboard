@extends('layouts.app')

@section('title', 'All Users')

@section('content')
    <h2 class="mb-4">All Users</h2>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">

            <div class="col-md-3">
                <label for="merchant_id" class="form-label">Filter by Merchant</label>
                <select name="merchant_id" id="merchant_id" class="form-select">
                    <option value="">All Merchants</option>
                    @foreach ($merchants as $merchant)
                        <option value="{{ $merchant->id }}" {{ request('merchant_id') == $merchant->id ? 'selected' : '' }}>
                            {{ $merchant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>



    <table class="table table-bordered table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Invited By</th>
                <th>Role(s)</th>
                <th style="width: 160px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        {{ $user->name }}
                        @if ($user->id === auth()->id())
                            <span class="badge bg-info text-dark">You</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{ $user->inviter?->name ?? 'System Seeder' }}
                    </td>
                    <td>
                        {{ $user->roles->pluck('label')->filter()->join(', ') ?: $user->getRoleNames()->join(', ') }}
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary">Manage</a>
                        @if ($user->id !== auth()->id())
                            <form method="POST" action="#" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
