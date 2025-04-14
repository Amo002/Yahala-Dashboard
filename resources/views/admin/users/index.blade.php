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
                                <td>
                                    @if($user->roles->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                                    <i class="bi bi-shield-check me-1"></i>
                                                    {{ $role->label ?? $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                            <i class="bi bi-shield-slash me-1"></i>
                                            No Roles
                                        </span>
                                    @endif
                                </td>
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
