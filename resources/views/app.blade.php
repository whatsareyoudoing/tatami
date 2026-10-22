<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tatami Indonesia</title>

    <!-- Memanggil CSS Bootstrap dari public/css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome-free/css/all.min.css') }}">

    @stack('styles')
</head>
<body class="bg-light">
    @yield('content')
    <!-- Memanggil JS Bootstrap dari public/js -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
