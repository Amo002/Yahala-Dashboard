<div class="col-md-3 col-lg-2 d-md-block bg-light border-end sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            @can('system-admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active bg-primary text-white' : '' }}"
                        href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
