@extends('layouts.app')

@section('title', 'Manage Role')

@section('content')
    <style>
        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .form-check-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        .form-check-input {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255, 255, 255, 0.25%29'/%3e%3c/svg%3e");
        }

        .form-check-input:checked {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-1">Manage Role</h2>
            <p class="text-body-secondary mb-0">{{ $role->label ?? $role->name }}</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back to Roles
        </a>
    </div>

    {{-- Flash Messages --}}
    <x-alert type="success" :message="session('status')" />
    <x-alert type="danger" :message="session('error')" />
    @if ($errors->any())
        <x-alert type="danger" :message="$errors->first()" />
    @endif

    {{-- Role Details --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-body-tertiary border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-shield-lock text-primary"></i>
                </div>
                <h5 class="mb-0">Role Details</h5>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                    <i class="bi bi-trash3 me-1"></i> Delete
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border h-100">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-shield-lock text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Role Name</h6>
                            <p class="text-body fw-bold mb-0">{{ $role->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border h-100">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-tag text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Display Label</h6>
                            <p class="text-body fw-bold mb-0">{{ $role->label ?? 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assigned Permissions --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-body-tertiary border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-key text-info"></i>
                </div>
                <h5 class="mb-0">Assigned Permissions</h5>
            </div>
            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#assignPermissionsModal">
                <i class="bi bi-key-fill me-1"></i> Assign Permissions
            </button>
        </div>
        <div class="card-body">
            @if ($role->permissions->isNotEmpty())
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($role->permissions as $permission)
                        <div class="d-flex align-items-center bg-info bg-opacity-10 rounded-pill px-3 py-1">
                            <i class="bi bi-check-circle-fill text-info me-2"></i>
                            <span class="text-info">{{ $permission->label }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <div class="bg-body-secondary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-key-slash fs-4"></i>
                    </div>
                    <p class="mb-0">No permissions assigned to this role</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Users Assigned --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-body-tertiary border-0">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="bi bi-people text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0">Users with this Role</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="border-0">Name</th>
                            <th class="border-0">Email</th>
                            <th class="border-0" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary bg-opacity-10 rounded p-2">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            @if ($user->id === auth()->id())
                                                <span
                                                    class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-body-secondary">{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('admin.users.manage', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-people fs-4 mb-2"></i><br>
                                    No users have this role
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Assign Permissions Modal --}}
    <div class="modal fade" id="assignPermissionsModal" tabindex="-1" aria-labelledby="assignPermissionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form method="POST" action="{{ route('admin.roles.assignPermissions', $role->id) }}"
                class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header border-0 bg-primary bg-gradient">
                    <h5 class="modal-title text-white">Assign Permissions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    {{-- Group Filter --}}
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-filter text-primary me-2"></i>
                                <select class="form-select form-select-sm shadow-sm"
                                    onchange="filterPermissions(this.value)">
                                    <option value="all" selected>All Permission Groups</option>
                                    @foreach ($availablePermissions as $group => $perms)
                                        <option value="{{ $group }}">{{ ucwords(str_replace('_', ' ', $group)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">
                                <span class="permission-count">0</span> Permissions Selected
                            </span>
                        </div>
                    </div>

                    @if (!empty($availablePermissions))
                        <div class="row g-3" id="permissionsContainer">
                            @foreach ($availablePermissions as $group => $permissions)
                                @foreach ($permissions as $permission)
                                    <div
                                        class="col-12 col-sm-6 col-md-4 col-lg-3 permission-group permission-{{ $group }}">
                                        <div
                                            class="d-flex align-items-center bg-body rounded p-2 border border-2 shadow-sm hover-shadow">
                                            <div class="form-check form-switch me-2">
                                                <input class="form-check-input permission-checkbox" type="checkbox"
                                                    name="permissions[]" value="{{ $permission['id'] }}"
                                                    id="perm_{{ $permission['id'] }}" onchange="updatePermissionCount()"
                                                    {{ $role->permissions->contains('id', $permission['id']) ? 'checked' : '' }}>
                                            </div>
                                            <label class="small fw-medium text-body user-select-none"
                                                for="perm_{{ $permission['id'] }}">
                                                {{ $permission['label'] ?? $permission['name'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">No assignable permissions available</div>
                    @endif
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <button type="button" class="btn btn-light border fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-medium px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Role Modal --}}
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.roles.update', $role->id) }}"
                class="modal-content border-0 shadow">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 bg-primary bg-gradient">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Role
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    <div class="mb-4">
                        <label for="editRoleName" class="form-label d-flex align-items-center">
                            <i class="bi bi-key text-primary me-2"></i>
                            System Name
                            <span class="badge bg-secondary-subtle text-secondary-emphasis ms-2">
                                <i class="bi bi-lock-fill me-1"></i>Protected
                            </span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0">
                                <i class="bi bi-shield-lock text-secondary"></i>
                            </span>
                            <input type="text" class="form-control bg-body-tertiary border-start-0 ps-0"
                                id="editRoleName" value="{{ $role->name }}" readonly disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleLabel" class="form-label d-flex align-items-center">
                            <i class="bi bi-tag text-primary me-2"></i>
                            Display Label
                        </label>
                        <input type="text" name="label" class="form-control shadow-sm" id="editRoleLabel"
                            value="{{ $role->label }}" required>
                        <small class="text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i>
                            This is shown to users throughout the system
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <button type="button" class="btn btn-light border fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-medium px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Role Modal --}}
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}"
                class="modal-content border-0 shadow">
                @csrf
                @method('DELETE')
                <div class="modal-header border-0 bg-danger bg-gradient">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Delete Role
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-trash3 text-danger fs-2"></i>
                        </div>
                        <h5 class="mb-2">Delete "{{ $role->label ?? $role->name }}"?</h5>
                        <p class="text-body-secondary mb-0">This action cannot be undone.</p>
                    </div>
                    <div class="alert alert-warning d-flex align-items-center mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                        <div>
                            This will remove this role from all assigned users.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <button type="button" class="btn btn-light border fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-medium px-4">
                        <i class="bi bi-trash3 me-1"></i>
                        Delete Role
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function filterPermissions(group) {
            const all = document.querySelectorAll('.permission-group');
            all.forEach(el => {
                el.style.display = group === 'all' || el.classList.contains('permission-' + group) ? 'block' :
                    'none';
            });
        }

        function updatePermissionCount() {
            const count = document.querySelectorAll('.permission-checkbox:checked').length;
            document.querySelector('.permission-count').textContent = count;
        }

        // Initialize permission count
        document.addEventListener('DOMContentLoaded', updatePermissionCount);
    </script>
@endsection
