<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary border-bottom shadow-sm px-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('welcome') }}">
            <i class="bi bi-house-door me-2"></i> Yahala
        </a>

        <div class="d-flex align-items-center gap-3">
            <button id="themeToggle" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-moon-stars"></i>
            </button>
            
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>
