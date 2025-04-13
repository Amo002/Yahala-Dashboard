<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Yahala Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">

    @include('components.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('components.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-bs-theme', savedTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>
</html>
