<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Login - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">ALWEFAQ</h1>
            <p class="text-gray-400 mt-2">Supervisor Login</p>
        </div>

        <form method="POST" action="{{ route('supervisor.login.post') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-300 text-sm font-medium mb-2">Email Address</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}"
                    class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                    placeholder="supervisor@example.com"
                    required autofocus>
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                    placeholder="Enter your password"
                    required>
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition duration-200">
                Login
            </button>
        </form>
    </div>
</body>
</html>
