@props([
    'route' => '',
    'icon' => 'circle',
    'label' => '',
    'badge' => null,   // angka notifikasi, e.g. pending count
])

@php
    $isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="sidebar-item flex items-center justify-between px-4 py-2 mx-2 rounded-md text-sm font-medium tracking-tight duration-200
          {{ $isActive
    ? 'bg-blue-600 text-white'
    : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">

    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-base">{{ $icon }}</span>
    {{ $label }}
    </div>
{{-- Badge notifikasi --}}
    @if($badge)
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold
                     {{ $isActive ? 'bg-white/20 text-white' : 'bg-red-500 text-white' }}">
            {{ $badge > 99 ? '99+' : $badge }}
        </span>
    @endif

</a>