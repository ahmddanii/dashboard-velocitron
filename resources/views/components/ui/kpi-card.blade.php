@props([
    'label'    => '',
    'value'    => '',
    'sub'      => '',
    'icon'     => 'payments',
    'color'    => 'blue',   // blue | green | orange | purple | red | cyan
    'trend'    => null,     // null | 'up' | 'down' | 'neutral'
    'trendVal' => null,
])

@php
$palette = [
    'blue'   => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',   'text' => 'text-blue-600 dark:text-blue-400',   'border' => 'border-blue-400',   'sub' => 'text-blue-500'],
    'green'  => ['bg' => 'bg-green-50 dark:bg-green-500/10',  'text' => 'text-green-600 dark:text-green-400',  'border' => 'border-green-400',  'sub' => 'text-green-600'],
    'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-500/10', 'text' => 'text-orange-600 dark:text-orange-400', 'border' => 'border-orange-400', 'sub' => 'text-orange-500'],
    'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400', 'border' => 'border-purple-400', 'sub' => 'text-purple-500'],
    'red'    => ['bg' => 'bg-red-50 dark:bg-red-500/10',    'text' => 'text-red-600 dark:text-red-400',    'border' => 'border-red-400',    'sub' => 'text-red-500'],
    'cyan'   => ['bg' => 'bg-cyan-50 dark:bg-cyan-500/10',   'text' => 'text-cyan-600 dark:text-cyan-400',   'border' => 'border-cyan-400',   'sub' => 'text-cyan-500'],
];
$p = $palette[$color] ?? $palette['blue'];

$trendIcon  = match($trend) { 'up' => 'trending_up', 'down' => 'trending_down', default => 'remove' };
$trendColor = match($trend) { 'up' => 'text-green-600', 'down' => 'text-red-500', default => 'text-on-surface-variant' };

// Auto-detect nilai panjang agar font diperkecil otomatis
$isLongValue = strlen((string) $value) > 10;
$isVeryLong  = strlen((string) $value) > 16;
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
    <div class="relative bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden
                hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group h-full">

        {{-- Accent top border --}}
        <div class="absolute top-0 left-0 right-0 h-0.5 {{ $p['border'] }} bg-current opacity-70"></div>

        <div class="p-4 flex items-start gap-3 h-full">

            {{-- Icon — lebih kecil agar proporsional --}}
            <div class="w-10 h-10 rounded-lg {{ $p['bg'] }} flex items-center justify-center {{ $p['text'] }} shrink-0
                        group-hover:scale-110 transition-transform duration-200 mt-0.5">
                <span class="material-symbols-outlined text-xl"
                      style="font-variation-settings:'FILL' 1; font-size: 20px;">
                    {{ $icon }}
                </span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">

                {{-- Label — allow wrap, font lebih kecil --}}
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest leading-tight mb-1.5">
                    {{ $label }}
                </p>

                {{-- Value — font size otomatis menyesuaikan panjang value --}}
                <p class="font-bold text-on-surface leading-tight break-words
                    @if($isVeryLong) text-base
                    @elseif($isLongValue) text-lg
                    @else text-2xl
                    @endif">
                    {{ $value }}
                </p>

                {{-- Sub / Trend --}}
                @if($trend && $trendVal)
                    <p class="text-[11px] font-semibold {{ $trendColor }} flex items-center gap-0.5 mt-1 flex-wrap">
                        <span class="material-symbols-outlined"
                              style="font-size:12px">{{ $trendIcon }}</span>
                        <span>{{ $trendVal }}</span>
                        @if($sub)
                            <span class="text-on-surface-variant font-normal">{{ $sub }}</span>
                        @endif
                    </p>
                @elseif($sub)
                    <p class="text-[11px] text-on-surface-variant mt-1 leading-snug">{{ $sub }}</p>
                @endif

            </div>
        </div>
    </div>
</div>