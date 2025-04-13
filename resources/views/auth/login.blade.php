<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Login - Yahala Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="text-center mb-4">
                    <h1 class="text-primary">
                        <i class="bi bi-house-door"></i> Yahala
                    </h1>
                    <p class="text-muted">Admin Dashboard</p>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Welcome Back</h3>

                        {{-- Flash Messages --}}
                        @if (session('status'))
                            <x-alert type="success" :message="session('status')" />
                        @endif

                        @if (session('error'))
                            <x-alert type="danger" :message="session('error')" />
                        @endif

                        @if ($errors->any())
                            <x-alert type="danger" :message="$errors->first()" />
                        @endif

                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus
                                        placeholder="Enter your email"
                                    >
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input 
                                        type="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        id="password" 
                                        name="password" 
                                        required
                                        placeholder="Enter your password"
                                    >
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input" 
                                    id="remember" 
                                    name="remember"
                                >
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center mt-3">
                    <a href="{{ route('welcome') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Back to welcome
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
