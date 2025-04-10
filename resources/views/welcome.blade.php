<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to Yahala Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-white text-dark">

    @include('components.navbar')

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card border border-0 shadow text-center p-4">
                    <i class="bi bi-shield-lock display-1 text-primary mb-4"></i>
                    <h1 class="display-5 fw-bold mb-3">Welcome to Yahala Dashboard</h1>
                    <p class="lead mb-4">Manage your merchants, users, and roles with ease.</p>

                    @auth
                        @if (session('user_role') === 'super_admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
