<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to Yahala Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
        }

        .icon-wrapper {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="welcome-card border-0 shadow-lg p-5">
                    <div class="icon-wrapper mb-4">
                        <i class="bi bi-shield-lock display-4 text-white"></i>
                    </div>
                    <h1 class="display-5 fw-bold text-center mb-3">Welcome to Yahala</h1>
                    <p class="lead text-center text-muted mb-4">Manage your merchants, users, and roles with ease.</p>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
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
