@extends('layouts.app')

@section('title', 'System Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">System Users</h2>
        @can('create-users')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-person-plus me-1"></i> Add User
            </button>
        @endcan
    </div>

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
                            @if (auth()->user()->can('edit-users') || auth()->user()->can('delete-users'))
                                <th style="width: 160px;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr></tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        {{ $user->name }}
                                        @if ($user->id === auth()->id())
                                            <span
                                                class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                                <i class="bi bi-person-check me-1"></i>You
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->inviter)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-add text-success me-1"></i>
                                        {{ $user->inviter->name }}
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-gear me-1"></i>System Seeder
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($user->roles->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($user->roles as $role)
                                            <span
                                                class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                                <i class="bi bi-shield-check me-1"></i>
                                                {{ $role->label ?? $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span
                                        class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <i class="bi bi-shield-slash me-1"></i>
                                        No Roles
                                    </span>
                                @endif
                            </td>
                            @if (auth()->user()->can('edit-users') || auth()->user()->can('delete-users'))
                                <td>
                                    <div class="d-flex gap-2">
                                        @can('edit-users')
                                            <a href="{{ route('admin.users.manage', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->can('edit-users') || auth()->user()->can('delete-users') ? '5' : '4' }}"
                                    class="text-center py-4">
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
