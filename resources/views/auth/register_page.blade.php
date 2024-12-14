<!DOCTYPE html>
<html lang="en" class="dark"> <!-- Permanently set dark mode -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Form</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Tailwind configuration (only dark mode needed)
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">

    <div class="bg-gray-800 shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-100 text-center mb-6">Register</h2>

        <form action="{{ route('register_store') }}" method="post" class="space-y-4">
            @csrf

            <!-- Name Input -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name" required
                    class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-gray-100">
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required
                    class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-gray-100">
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required
                    class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-gray-100">
            </div>

            <!-- Google reCAPTCHA -->
            <div>
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}"></div>
                @if ($errors->has('captcha'))
                    <p class="text-red-400 text-sm mt-1">{{ $errors->first('captcha') }}</p>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                    Register
                </button>
            </div>
        </form>
    </div>

</body>
</html>
