<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Admin Tatami Indonesia</title>

    <link rel="stylesheet" href="@asset('css/bootstrap.min.css')">
    <link rel="stylesheet" href="@asset('fontawesome-free/css/all.min.css')">
    <link rel="stylesheet" href="@asset('css/admin.css')">
    @stack('styles')
</head>
<body class="bg-light">
    @include('partials.admin.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('partials.admin.sidebar')

            <main class="col-md-9 col-lg-10 px-md-4 py-4">
                @include('partials.admin.flash')
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Admin tidak memakai jQuery: cukup Bootstrap + JS kecil milik sendiri --}}
    <script src="@asset('js/bootstrap.bundle.min.js')"></script>
    <script src="@asset('js/admin.js')"></script>
    @stack('scripts')
</body>
</html>
