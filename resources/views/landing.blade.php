<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>DataCore — Modern Data Architecture</title>

    {{-- Tailwind dari Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400;
        }

        .sql-gradient {
            background: linear-gradient(135deg, #0b1c30 0%, #131b2e 100%);
        }
    </style>
</head>

<body class="text-gray-800 bg-gray-50">

    <!-- NAVBAR -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur border-b">
        <div class="flex justify-between items-center h-16 px-6 max-w-7xl mx-auto">

            <div class="text-xl font-bold">DataCore</div>

            <div class="hidden md:flex gap-8 items-center">
                <a class="text-blue-600 border-b-2 border-blue-600 h-16 flex items-center">Features</a>
                <a class="text-gray-600 hover:text-blue-600">Solutions</a>
                <a class="text-gray-600 hover:text-blue-600">Pricing</a>
            </div>

            <div class="flex items-center gap-4">

                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-600">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-600">
                        Sign In
                    </a>
                @endauth

                <a href="{{ route('dashboard') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    Get Started
                </a>
            </div>

        </div>
    </nav>

    <main class="pt-16">

        <!-- HERO -->
        <section class="py-24 px-6">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">

                <div>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full mb-6 font-bold">
                        V2.4 NOW LIVE
                    </span>

                    <h1 class="text-4xl font-bold mb-6">
                        Modern Data Architecture Built for Precision.
                    </h1>

                    <p class="text-gray-600 mb-10 max-w-lg">
                        Streamline your entire data lifecycle with a powerful BI platform.
                    </p>

                    <div class="flex gap-4">
                        <a href="{{ route('dashboard') }}"
                            class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold">
                            Start Building
                        </a>

                        <button class="border px-8 py-4 rounded-lg">
                            ▶ Watch Demo
                        </button>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-2xl p-10 text-center text-white">
                    <span class="material-symbols-outlined text-6xl opacity-40">database</span>
                    <p class="mt-4 opacity-60">Dashboard Preview</p>
                </div>

            </div>
        </section>

        <!-- TRUST -->
        <section class="py-12 border-y bg-white text-center">
            <p class="text-xs uppercase mb-6 text-gray-400">Trusted by</p>
            <div class="flex justify-center gap-10 opacity-40 text-xl font-bold">
                <span>Amazon</span>
                <span>Google</span>
                <span>Netflix</span>
                <span>Microsoft</span>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="py-24 px-6 bg-gray-100">
            <div class="max-w-7xl mx-auto text-center">

                <h2 class="text-2xl font-bold mb-6">Engineered for Performance</h2>

                <div class="grid md:grid-cols-3 gap-8 mt-12">

                    <div class="bg-white p-6 rounded-xl shadow">
                        <span class="material-symbols-outlined text-blue-600">automation</span>
                        <h3 class="font-semibold mt-3">Automated Pipelines</h3>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow">
                        <span class="material-symbols-outlined text-blue-600">security</span>
                        <h3 class="font-semibold mt-3">Global Security</h3>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow">
                        <span class="material-symbols-outlined text-blue-600">monitoring</span>
                        <h3 class="font-semibold mt-3">Real-time Analytics</h3>
                    </div>

                </div>

            </div>
        </section>

        <!-- CTA -->
        <section class="py-24 text-center bg-gray-900 text-white">
            <h2 class="text-3xl font-bold mb-6">Join the Future of Data</h2>

            <a href="{{ route('dashboard') }}" class="bg-blue-600 px-10 py-4 rounded-lg font-semibold">
                Get Started
            </a>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="py-10 text-center text-gray-500">
        © {{ date('Y') }} DataCore Systems
    </footer>

</body>

</html>