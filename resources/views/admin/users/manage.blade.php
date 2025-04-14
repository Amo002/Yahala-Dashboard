@extends('layouts.app')

@section('title', 'Manage User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-1">Manage User</h2>
            <p class="text-body-secondary mb-0">{{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    {{-- Flash --}}
    <x-alert type="success" :message="session('status')" />
    <x-alert type="danger" :message="session('error')" />

    {{-- User Details --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-body-tertiary border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-person-circle text-primary"></i>
                </div>
                <h5 class="mb-0">User Profile</h5>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
                    <i class="bi bi-pencil me-1"></i> Edit Profile
                </button>
                <button class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                    <i class="bi bi-trash3 me-1"></i> Delete User
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-person text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Full Name</h6>
                            <p class="text-body fw-bold mb-0">{{ $user->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-envelope text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Email Address</h6>
                            <p class="text-body fw-bold mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border h-100">
                        <div class="flex-shrink-0">
                            @if ($user->merchant_id === 1)
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-gear-fill text-primary fs-4"></i>
                                </div>
                            @else
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-shop text-success fs-4"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">
                                {{ $user->merchant_id === 1 ? 'Admin Team' : 'Merchant Name' }}
                            </h6>
                            <p class="text-body fw-bold mb-0">
                                {{ $user->merchant_id === 1 ? 'System Administrator' : $user->merchant->name ?? 'Not set' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-person-plus text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Invited By</h6>
                            <p class="text-body fw-bold mb-0">{{ $user->invited_by ?? 'System' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border">
                        <div class="flex-shrink-0">
                            <div
                                class="bg-{{ $user->email_verified_at ? 'success' : 'danger' }} bg-opacity-10 rounded-circle p-3">
                                <i
                                    class="bi bi-{{ $user->email_verified_at ? 'check-circle' : 'x-circle' }} text-{{ $user->email_verified_at ? 'success' : 'danger' }} fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Verification Status</h6>
                            <p class="text-body fw-bold mb-0">
                                {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                @if ($user->email_verified_at)
                                    <small class="text-success ms-2">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $user->email_verified_at->format('M d, Y') }}
                                    </small>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded bg-body-tertiary border">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-check text-secondary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-body-secondary mb-1">Member Since</h6>
                            <p class="text-body fw-bold mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assigned Role Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-body-tertiary border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-shield text-primary"></i>
                </div>
                <h5 class="mb-0">Assigned Roles</h5>
            </div>
            <div>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                    <i class="bi bi-shield-plus me-1"></i> Change Role
                </button>
                @if ($user->roles->isNotEmpty())
                    <button class="btn btn-outline-danger btn-sm ms-2" data-bs-toggle="modal"
                        data-bs-target="#unassignRoleModal">
                        <i class="bi bi-shield-minus me-1"></i> Unassign All Roles
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if ($user->roles->isNotEmpty())
                @foreach ($user->roles as $role)
                    <div class="d-flex align-items-center justify-content-between p-3 rounded bg-body-tertiary border mb-3">
                        <div>
                            <h6 class="mb-2 text-body">{{ $role->label ?? $role->name }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($role->permissions as $permission)
                                    <div class="d-flex align-items-center bg-info bg-opacity-10 rounded-pill px-3 py-1">
                                        <i class="bi bi-check-circle-fill text-info me-2"></i>
                                        <span class="text-info">{{ $permission->label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <a href="{{ route('admin.roles.manage', $role->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-gear me-1"></i> Manage Role
                        </a>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4 text-body-secondary">
                    <div class="bg-body-tertiary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-shield-slash fs-4"></i>
                    </div>
                    <p class="mb-0">No role assigned to this user</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}"
                class="modal-content border-0 shadow needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-header border-0 bg-primary bg-gradient">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit User Profile
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    {{-- Full Name --}}
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-person text-primary me-2"></i>
                            Full Name
                        </label>
                        <input type="text" name="name" class="form-control shadow-sm"
                            value="{{ old('name', $user->name) }}" required>
                        <div class="invalid-feedback">Please enter a name.</div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            Email Address
                        </label>
                        <input type="email" name="email" class="form-control shadow-sm"
                            value="{{ old('email', $user->email) }}" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    {{-- Merchant Selection
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-shop text-primary me-2"></i>
                            Merchant
                        </label>
                        <div class="d-flex flex-column gap-2">
                            @foreach ($merchants as $merchant)
                                <div class="form-check p-3 rounded bg-body-tertiary border">
                                    <input class="form-check-input" type="radio" name="merchant_id"
                                        id="merchant_{{ $merchant->id }}" value="{{ $merchant->id }}"
                                        {{ $user->merchant_id == $merchant->id ? 'checked' : '' }} required>
                                    <label class="form-check-label d-flex align-items-center"
                                        for="merchant_{{ $merchant->id }}">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-shop text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $merchant->name }}</h6>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="invalid-feedback">Please select a merchant.</div>
                    </div> --}}
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <button type="button" class="btn btn-light border fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-medium px-4">
                        <i class="bi bi-check-circle me-1"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete User Modal --}}
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-danger bg-gradient">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Delete User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-person-x text-danger fs-2"></i>
                        </div>
                        <h5 class="mb-2">Delete "{{ $user->name }}"?</h5>
                        <p class="text-body-secondary mb-0">This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <button type="button" class="btn btn-light border fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger fw-medium px-4">
                        <i class="bi bi-trash3 me-1"></i>
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Assign Role Modal --}}
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-primary bg-gradient">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-shield-plus me-1"></i>
                        Change User Role
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    <form method="POST" action="{{ route('admin.users.assignRole', $user->id) }}"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                        <i class="bi bi-shield text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Assign Roles</h6>
                                        <small class="text-body-secondary">Toggle roles to manage user permissions</small>
                                    </div>
                                </div>
                                <div class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                    <span id="selectedRolesCount">0</span> Roles Selected
                                </div>
                            </div>

                            <div class="vstack gap-2">
                                @foreach ($availableRoles as $role)
                                    <div class="list-group-item border rounded-3 bg-body-secondary bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between p-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="p-2 rounded-2 bg-primary-subtle">
                                                    <i class="bi bi-shield-check text-primary-emphasis"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-body">{{ $role->label ?? $role->name }}
                                                    </div>
                                                    @if ($role->description)
                                                        <div class="small text-body-secondary">{{ $role->description }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    name="role_ids[]" id="role_{{ $role->id }}"
                                                    value="{{ $role->id }}" onchange="updateRoleCount()"
                                                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                                <label class="form-check-label visually-hidden"
                                                    for="role_{{ $role->id }}">
                                                    Toggle {{ $role->label ?? $role->name }} role
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div
                            class="alert alert-primary bg-primary-subtle border-primary-subtle d-flex align-items-center mb-0">
                            <i class="bi bi-info-circle-fill text-primary-emphasis me-2"></i>
                            <div class="text-primary-emphasis">
                                Changes to role assignments will automatically update the user's permissions.
                            </div>
                        </div>

                        <div class="modal-footer border-0 bg-body-tertiary px-4">
                            <button type="button" class="btn btn-light border fw-medium"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary fw-medium px-4">
                                <i class="bi bi-shield-check me-1"></i>
                                Assign Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Unassign Role Modal --}}
    <div class="modal fade" id="unassignRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-danger bg-gradient">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-shield-minus me-1"></i>
                        Unassign All Roles
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-body-tertiary border-top border-bottom p-4">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-shield-x text-danger fs-2"></i>
                        </div>
                        <h5 class="mb-2">Remove All Roles from "{{ $user->name }}"?</h5>
                        <p class="text-body-secondary mb-0">This user will lose all associated roles and permissions.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-body-tertiary px-4">
                    <form method="POST" action="{{ route('admin.users.unassignRole', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-light border fw-medium"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger fw-medium px-4">
                            <i class="bi bi-shield-x me-1"></i>
                            Unassign Role
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Only essential form-check-input styling that Bootstrap doesn't provide */
        .form-check-input {
            width: 3em;
            height: 1.5em;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .form-check-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        [data-bs-theme="light"] .list-group-item:hover {
            background-color: var(--bs-light) !important;
        }

        [data-bs-theme="dark"] .list-group-item:hover {
            background-color: var(--bs-dark) !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    function updateRoleCount(modal) {
        const counter = modal.querySelector('#selectedRolesCount');
        const checked = modal.querySelectorAll('input[name="role_ids[]"]:checked').length;
        if (counter) counter.textContent = checked;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const modalEl = document.getElementById('assignRoleModal');

        // Ensure modal is initialized properly via Bootstrap API
        const bsModal = new bootstrap.Modal(modalEl);

        modalEl.addEventListener('shown.bs.modal', () => {
            updateRoleCount(modalEl);

            const checkboxes = modalEl.querySelectorAll('input[name="role_ids[]"]');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => updateRoleCount(modalEl));
            });
        });
    });
</script>
@endpush
