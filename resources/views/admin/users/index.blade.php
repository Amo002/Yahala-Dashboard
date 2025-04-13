@extends('layouts.app')

@section('title', 'System Users')

@section('content')
    <h2 class="mb-4">System Users</h2>

    <x-alert type="success" :message="session('status')" />
    <x-alert type="danger" :message="session('error')" />

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
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
                                        <span class="badge bg-info">You</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->inviter?->name ?? 'System Seeder' }}</td>
                                <td>{{ $user->roles->pluck('label')->filter()->join(', ') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.manage', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-people display-6"></i>
                                        <p class="mt-2 mb-0">No users found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
