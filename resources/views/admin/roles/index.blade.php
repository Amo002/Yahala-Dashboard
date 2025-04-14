@extends('layouts.app')

@section('title', 'System Roles')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">System Roles</h2>
        @can('create-roles')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="bi bi-plus-circle me-1"></i> Add Role
            </button>
        @endcan
    </div>

    {{-- Flash Messages --}}
    <x-alert type="success" :message="session('status')" />
    <x-alert type="danger" :message="session('error')" />
    <x-alert type="danger" :message="$errors->first('name')" />

    {{-- Roles Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Name</th>
                            <th class="border-0">Permissions</th>
                            <th class="border-0">Created By</th>
                            <th class="border-0" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $role->label ?? $role->name }}</div>
                                    <small class="text-muted">{{ $role->name }}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($role->permissions as $permission)
                                            <div class="d-flex align-items-center bg-info bg-opacity-10 rounded-pill px-3 py-1">
                                                <i class="bi bi-check-circle-fill text-info me-2"></i>
                                                <span class="text-info">{{ $permission->label }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge text-primary me-2"></i>
                                        <span class="fw-medium">{{ $role->creator_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @can('edit-roles')
                                            <a href="{{ route('admin.roles.manage', $role->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete-roles')
                                            @if ($role->users_count === 0)
                                                <form method="POST" action="#">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-shield-lock display-6"></i>
                                        <p class="mt-2 mb-0">No roles found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Role Modal --}}
    @can('create-roles')
        <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.roles.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createRoleModalLabel">Add Role</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role Name</label>
                            <input type="text" name="name" id="roleName" class="form-control" required>
                            <small class="text-muted">
                                Use lowercase with underscores (e.g. <code>user_editor</code>). Label will be auto-formatted.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
@endsection
