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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .pipeline-connector {
            background-image: linear-gradient(90deg, #cbd5e1 50%, transparent 50%);
            background-size: 8px 1px;
            background-repeat: repeat-x;
        }
    </style>

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