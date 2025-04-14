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

        .hover-shadow {
            transition: all 0.2s ease-in-out;
        }

        .hover-shadow:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        /* Custom switch styling */
        .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        /* Dark mode adjustments */
        [data-bs-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-primary-rgb), 0.2);
        }

        [data-bs-theme="dark"] .hover-shadow:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.2) !important;
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
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border h-100">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-person-badge text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Created By</h6>
                            <p class="text-body fw-bold mb-0">{{ $role->creator_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border h-100">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar3 text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Created At</h6>
                            <p class="text-body fw-bold mb-0">{{ $role->created_at->format('M d, Y') }}</p>
                            <small class="text-muted">{{ $role->created_at->format('h:i A') }}</small>
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
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="bi bi-people text-primary"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">Users with this Role</h5>
                    </div>
                </div>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignUsersModal">
                    <i class="bi bi-person-plus me-1"></i> Assign Users
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="border-0">Name</th>
                            <th class="border-0">Email</th>
                            <th class="border-0" style="width: 150px;">Actions</th>
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
                                                    class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                                    You</span>
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
                                    <!-- Unassign button with modal trigger -->
                                    <button type="button" class="btn btn-sm btn-outline-danger unassign-user-btn"
                                        data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                        data-role-id="{{ $role->id }}" data-bs-toggle="modal"
                                        data-bs-target="#unassignUserModal">
                                        <i class="bi bi-shield-x"></i>
                                    </button>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleAllGroups()">
                            <i class="bi bi-arrows-expand me-1"></i>
                            <span class="toggle-text">Expand All</span>
                        </button>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">
                            <span class="permission-count">0</span> Permissions Selected
                        </span>
                    </div>

                    @if (!empty($availablePermissions))
                        <div class="accordion" id="permissionsAccordion">
                            @foreach ($availablePermissions as $group => $permissions)
                                <div class="accordion-item border-0 bg-transparent mb-3">
                                    <h2 class="accordion-header rounded-3 shadow-sm">
                                        <button class="accordion-button collapsed bg-body rounded-3" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $group }}"
                                            aria-expanded="false">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-folder2 text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ ucwords(str_replace('_', ' ', $group)) }}</span>
                                                <span class="badge bg-primary-subtle text-primary-emphasis ms-2 group-count-{{ $group }}">0</span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $group }}" class="accordion-collapse collapse" data-bs-parent="">
                                        <div class="accordion-body p-3">
                                            <div class="row g-3">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-12 col-md-6">
                                                        <div class="d-flex align-items-center bg-body rounded-3 p-3 border shadow-sm hover-shadow transition-all">
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input permission-checkbox permission-group-{{ $group }}"
                                                                    type="checkbox"
                                                                    name="permissions[]"
                                                                    value="{{ $permission['id'] }}"
                                                                    id="perm_{{ $permission['id'] }}"
                                                                    onchange="updatePermissionCount(); updateGroupCount('{{ $group }}')"
                                                                    {{ $role->permissions->contains('id', $permission['id']) ? 'checked' : '' }}>
                                                            </div>
                                                            <label class="small fw-medium text-body user-select-none w-100" 
                                                                for="perm_{{ $permission['id'] }}">
                                                                {{ $permission['label'] ?? $permission['name'] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    @if ($userCount > 0)
                        <div class="alert alert-warning d-flex align-items-center mb-0">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            <div>
                                This will remove this role from <strong>{{ $userCount }}</strong> assigned
                                {{ Str::plural('user', $userCount) }}.
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center mb-0">
                            <i class="bi bi-info-circle-fill text-info me-2"></i>
                            <div>
                                This role is not assigned to any users. It can be safely deleted.
                            </div>
                        </div>
                    @endif
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

    {{-- Assign Users Modal --}}
    <form method="POST" action="{{ route('admin.roles.assignUsers', $role->id) }}">
        @csrf
        <div class="modal fade" id="assignUsersModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white d-flex align-items-center gap-2">
                            <i class="bi bi-people"></i>
                            Assign Users to Role
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-shield text-primary"></i>
                                <div>
                                    <div class="fw-medium">{{ $role->label ?? $role->name }}</div>
                                    <div class="text-body-secondary small">Select users to assign this role</div>
                                </div>
                                <div class="badge bg-primary text-white ms-auto">
                                    <span id="selectedUsersCount">0</span> Users Selected
                                </div>
                            </div>
                            <div class="vstack gap-2">
                                @foreach ($allUsers ?? [] as $user)
                                    <div
                                        class="d-flex align-items-center justify-content-between p-3 rounded bg-body-secondary">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2 rounded bg-primary-subtle">
                                                <i class="bi bi-person text-primary-emphasis"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-body">{{ $user->name }}</div>
                                                <div class="small text-body-secondary">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="user_ids[]"
                                                id="user_{{ $user->id }}" value="{{ $user->id }}"
                                                onchange="updateUserCount()"
                                                {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="alert alert-primary bg-primary-subtle border-primary-subtle m-0">
                            <div class="d-flex gap-2 text-primary-emphasis">
                                <i class="bi bi-info-circle-fill"></i>
                                <div>Selected users will be granted all permissions associated with this role.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-body-tertiary px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="bi bi-person-plus"></i>
                            Assign Users
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Unassign User Modal --}}
    <div class="modal fade" id="unassignUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="unassignUserForm" method="POST" class="modal-content border-0 shadow">
                @csrf
                @method('DELETE')
                <div class="modal-header border-0 bg-danger bg-gradient">
                    <h5 class="modal-title text-white">Unassign Role from User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary p-4">
                    <p>Are you sure you want to unassign this role from <strong id="unassignUserName"></strong>?</p>
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Unassign Role</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Existing styles... */

        .hover-shadow {
            transition: all 0.2s ease-in-out;
        }

        .hover-shadow:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        /* Custom switch styling */
        .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .form-check-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        /* Dark mode adjustments */
        [data-bs-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-primary-rgb), 0.2);
        }

        [data-bs-theme="dark"] .hover-shadow:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.2) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let allExpanded = false;

        function toggleAllGroups() {
            const accordionButtons = document.querySelectorAll('.accordion-button');
            const accordionCollapses = document.querySelectorAll('.accordion-collapse');
            const toggleButton = document.querySelector('.toggle-text');
            
            allExpanded = !allExpanded;
            
            accordionButtons.forEach(button => {
                if (allExpanded) {
                    button.classList.remove('collapsed');
                    button.setAttribute('aria-expanded', 'true');
                } else {
                    button.classList.add('collapsed');
                    button.setAttribute('aria-expanded', 'false');
                }
            });
            
            accordionCollapses.forEach(collapse => {
                if (allExpanded) {
                    collapse.classList.add('show');
                } else {
                    collapse.classList.remove('show');
                }
            });
            
            toggleButton.textContent = allExpanded ? 'Collapse All' : 'Expand All';
        }

        function updateGroupCount(group) {
            const groupCheckboxes = document.querySelectorAll(`.permission-group-${group}:checked`);
            const countBadge = document.querySelector(`.group-count-${group}`);
            if (countBadge) {
                countBadge.textContent = groupCheckboxes.length;
            }
        }

        function updatePermissionCount() {
            const count = document.querySelectorAll('.permission-checkbox:checked').length;
            const permissionCountEl = document.querySelector('.permission-count');
            if (permissionCountEl) {
                permissionCountEl.textContent = count;
            }
        }

        // Initialize counts
        document.addEventListener('DOMContentLoaded', () => {
            updatePermissionCount();
            // Update counts for all groups
            @foreach ($availablePermissions as $group => $permissions)
                updateGroupCount('{{ $group }}');
            @endforeach
        });

        function updateUserCount() {
            const modal = document.getElementById('assignUsersModal');
            if (!modal) return;
            const checkboxes = modal.querySelectorAll('input[name="user_ids[]"]:checked');
            const counter = modal.querySelector('#selectedUsersCount');
            if (counter) {
                counter.textContent = checkboxes.length;
            }
        }

        // Unassign User Modal script:
        document.addEventListener('DOMContentLoaded', () => {
            const unassignButtons = document.querySelectorAll('.unassign-user-btn');
            const unassignModal = document.getElementById('unassignUserModal');
            const unassignForm = document.getElementById('unassignUserForm');
            const unassignUserNameEl = document.getElementById('unassignUserName');

            unassignButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const userId = button.getAttribute('data-user-id');
                    const userName = button.getAttribute('data-user-name');
                    const roleId = button.getAttribute('data-role-id');
                    unassignUserNameEl.textContent = userName;
                    // Set form action to correct route, e.g. /admin/roles/ROLE_ID/unassign-user/USER_ID
                    unassignForm.action = `/admin/roles/${roleId}/unassign-user/${userId}`;
                });
            });
        });
    </script>
@endpush
