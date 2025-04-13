<div class="col-md-3 col-lg-2 d-md-block bg-body-tertiary border-end sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : 'text-body' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            @can('system-admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active bg-primary text-white' : 'text-body' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
            @endcan

            @can('admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.merchants.index') ? 'active bg-primary text-white' : 'text-body' }}"
                       href="{{ route('admin.merchants.index') }}">
                        <i class="bi bi-shop me-2"></i> Merchants
                    </a>
                </li>
            @endcan

            @can('system-admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active bg-primary text-white' : 'text-body' }}"
                       href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-shield-lock me-2"></i> Roles
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
