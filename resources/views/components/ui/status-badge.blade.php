@props([
    'status' => 'pending'
])

@php

    $styles = match ($status) {

        'approved' =>

        'bg-green-50
                text-green-700
                border-green-200
                dark:bg-green-500/10
                dark:text-green-400
                dark:border-green-500/20',

        'rejected' =>

        'bg-red-50
                text-red-700
                border-red-200
                dark:bg-red-500/10
                dark:text-red-400
                dark:border-red-500/20',

        'historical' =>

        'bg-blue-50
                text-blue-700
                border-blue-200
                dark:bg-blue-500/10
                dark:text-blue-400
                dark:border-blue-500/20',

        default =>

        'bg-amber-50
                text-amber-700
                border-amber-200
                dark:bg-amber-500/10
                dark:text-amber-400
                dark:border-amber-500/20',
    };

@endphp

<span class="inline-flex items-center gap-1.5
    px-3 py-1 rounded-full
    text-xs font-bold border
    {{ $styles }}">

@if($status === 'approved')

    <span class="material-symbols-outlined text-sm">

        check_circle

        </span>

@elseif($status === 'rejected')

    <span class="material-symbols-outlined text-sm">

        cancel

        </span>

@elseif($status === 'historical')

    <span class="material-symbols-outlined text-sm">inventory</span>

@else

    <span class="material-symbols-outlined text-sm">

        schedule

        </span>

@endif

    {{ ucfirst($status) }}

</span>