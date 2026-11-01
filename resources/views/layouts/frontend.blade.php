<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') · Tatami Indonesia</title>

    <link rel="stylesheet" href="@asset('css/bootstrap.min.css')">
    <link rel="stylesheet" href="@asset('fontawesome-free/css/all.min.css')">
    <link rel="stylesheet" href="@asset('css/app.css')">
    @stack('styles')
</head>
<body class="bg-light">
    {{-- Halaman cukup mengisi @section('content'); navbar dan footer otomatis --}}
    @include('partials.frontend.navbar')

    @yield('content')

    @include('partials.frontend.footer')

    <script src="@asset('js/jquery-3.7.1.min.js')"></script>
    <script src="@asset('js/bootstrap.bundle.min.js')"></script>
    {{-- Konfirmasi untuk form dengan atribut data-confirm (misalnya hapus barang di keranjang) --}}
    <script src="@asset('js/admin.js')"></script>
    @include('partials.frontend.translate')
    @stack('scripts')
</body>
</html>
