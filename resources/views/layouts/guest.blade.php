<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') · Tatami Indonesia</title>

    <link rel="stylesheet" href="@asset('css/bootstrap.min.css')">
    <link rel="stylesheet" href="@asset('fontawesome-free/css/all.min.css')">
    <link rel="stylesheet" href="@asset('css/admin.css')">
</head>
<body class="bg-light">
    <main class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        @yield('content')
    </main>

    <script src="@asset('js/bootstrap.bundle.min.js')"></script>
    @stack('scripts')
</body>
</html>
