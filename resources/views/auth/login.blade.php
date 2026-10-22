<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Manual Laravel 12</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>

        @error('username')
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ url('/login') }}" method="POST">
            @csrf {{-- WAJIB untuk keamanan dari serangan CSRF --}}

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">Login</button>
        </form>
    </div>

</body>
</html>
