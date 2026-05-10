@props([
    'status' => 'pending'
])

@php

    $styles = match ($status) {

        'approved' =>

        'bg-green-50
                text-green-700
                border-green-200',

        'rejected' =>

        'bg-red-50
                text-red-700
                border-red-200',

        default =>

        'bg-amber-50
                text-amber-700
                border-amber-200',
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

@else

    <span class="material-symbols-outlined text-sm">

        schedule

        </span>

@endif

    {{ ucfirst($status) }}

</span>