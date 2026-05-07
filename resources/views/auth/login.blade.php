<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - DataCore</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
            background-image:
                radial-gradient(#e2e8f0 0.5px, transparent 0.5px),
                radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-6">

    <div class="w-full max-w-[440px]">

        <!-- LOGO -->
        <div class="flex flex-col items-center mb-8">
            <a href="{{ url('/') }}" class="flex items-center gap-2 mb-2">
                <div class="w-10 h-10 bg-[#2170e4] rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">DB</span>
                </div>
                <h1 class="font-semibold text-lg tracking-tight">DataCore</h1>
            </a>
            <p class="text-gray-500 text-sm">High-integrity data warehousing.</p>
        </div>

        <!-- CARD -->
        <div class="bg-white border border-[#c6c6cd] rounded-xl p-8 shadow-sm">

            <div class="my-6">
                <h2 class="text-xl text-center font-semibold text-gray-900 mb-2">
                    Welcome back
                </h2>
                <p class="text-gray-500 text-sm">
                    Enter your credentials to access your data workspace.
                </p>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full mt-1 px-3 py-2 bg-white border border-[#c6c6cd] rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-[#2170e4]">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Password
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-[11px] text-[#2170e4] font-semibold hover:underline">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <input type="password" name="password" required
                        class="w-full mt-1 px-3 py-2 bg-white border border-[#c6c6cd] rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-[#2170e4]">
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Remember me</span>
                </div>

                <!-- BUTTON -->
                <button
                    class="w-full bg-[#2170e4] text-white py-2.5 my-10 rounded-lg font-semibold hover:opacity-90 transition">
                    Sign In
                </button>

            </form>

        </div>

        <!-- FOOTER -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#2170e4] font-semibold hover:underline">
                Register
            </a>
        </p>

    </div>

</body>

</html>