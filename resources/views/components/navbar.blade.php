<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('welcome') }}">
            <i class="bi bi-house-door me-2"></i> Yahala
        </a>

        <div class="ms-auto">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>
