<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DataCore' }} — Warehouse Central</title>

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    {{-- Vite: Tailwind CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js'])

    @stack('styles')
</head>

<body class="text-on-surface">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Top Bar --}}
    @include('layouts.partials.topbar')

    {{-- Main Content --}}
    <div id="app-wrapper" class="ml-64 mt-14">
        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>