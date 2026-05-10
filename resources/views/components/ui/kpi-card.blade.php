@props([
    'label'    => '',
    'value'    => '',
    'sub'      => '',
    'icon'     => 'payments',
    'color'    => 'blue',   // blue | green | orange | purple | red | cyan
    'trend'    => null,     // null | 'up' | 'down' | 'neutral'
    'trendVal' => null,     // e.g. '+12.4%'
])

@php
$palette = [
    'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'border' => 'border-blue-400',   'sub' => 'text-blue-500'],
    'green'  => ['bg' => 'bg-green-50',  'text' => 'text-green-600',  'border' => 'border-green-400',  'sub' => 'text-green-600'],
    'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-400', 'sub' => 'text-orange-500'],
    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-400', 'sub' => 'text-purple-500'],
    'red'    => ['bg' => 'bg-red-50',    'text' => 'text-red-600',    'border' => 'border-red-400',    'sub' => 'text-red-500'],
    'cyan'   => ['bg' => 'bg-cyan-50',   'text' => 'text-cyan-600',   'border' => 'border-cyan-400',   'sub' => 'text-cyan-500'],
];
$p = $palette[$color] ?? $palette['blue'];

$trendIcon  = match($trend) { 'up' => 'trending_up', 'down' => 'trending_down', default => 'remove' };
$trendColor = match($trend) { 'up' => 'text-green-600', 'down' => 'text-red-500', default => 'text-slate-400' };
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
    <div class="relative bg-white border border-outline-variant rounded-xl overflow-hidden
                hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">

        {{-- Accent top border --}}
        <div class="absolute top-0 left-0 right-0 h-0.5 {{ $p['border'] }} bg-current opacity-70"></div>

        <div class="p-5 flex items-center gap-4">

            {{-- Icon --}}
            <div class="w-12 h-12 rounded-xl {{ $p['bg'] }} flex items-center justify-center {{ $p['text'] }} shrink-0
                        group-hover:scale-110 transition-transform duration-200">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">
                    {{ $icon }}
                </span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider mb-1">
                    {{ $label }}
                </p>
                <p class="font-headline-md text-headline-md text-on-surface truncate">
                    {{ $value }}
                </p>

                {{-- Sub / Trend --}}
                @if($trend && $trendVal)
                    <p class="text-xs font-semibold {{ $trendColor }} flex items-center gap-0.5 mt-0.5">
                        <span class="material-symbols-outlined" style="font-size:13px">{{ $trendIcon }}</span>
                        {{ $trendVal }}
                        @if($sub)
                            <span class="text-on-surface-variant font-normal ml-1">{{ $sub }}</span>
                        @endif
                    </p>
                @elseif($sub)
                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $sub }}</p>
                @endif
            </div>
        </div>
    </div>
</div>