@props([
    'title'    => 'Intelligence',
    'subtitle' => '',
    'stats'    => [],   // array of ['label', 'value', 'color' => green|red|blue|amber|cyan]
    'insights' => [],   // array of string
    'icon'     => 'insights',
    'color'    => 'blue', // warna aksen icon insight
])

@php
$iconPalette = [
    'blue'   => ['bg' => 'bg-blue-100 dark:bg-blue-500/10',   'text' => 'text-blue-600 dark:text-blue-400'],
    'green'  => ['bg' => 'bg-green-100 dark:bg-green-500/10',  'text' => 'text-green-600 dark:text-green-400'],
    'cyan'   => ['bg' => 'bg-cyan-100 dark:bg-cyan-500/10',   'text' => 'text-cyan-600 dark:text-cyan-400'],
    'orange' => ['bg' => 'bg-orange-100 dark:bg-orange-500/10', 'text' => 'text-orange-600 dark:text-orange-400'],
    'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400'],
    'amber'  => ['bg' => 'bg-amber-100 dark:bg-amber-500/10',  'text' => 'text-amber-600 dark:text-amber-400'],
];
$ip = $iconPalette[$color] ?? $iconPalette['blue'];

$statPalette = [
    'green' => ['bg' => 'bg-green-50 dark:bg-green-500/10', 'border' => 'border-green-100 dark:border-green-500/20', 'label' => 'text-green-700 dark:text-green-400', 'value' => 'text-green-900 dark:text-green-50', 'bar' => 'bg-green-400'],
    'red'   => ['bg' => 'bg-red-50 dark:bg-red-500/10',   'border' => 'border-red-100 dark:border-red-500/20',   'label' => 'text-red-700 dark:text-red-400',   'value' => 'text-red-900 dark:text-red-50',   'bar' => 'bg-red-400'],
    'blue'  => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',  'border' => 'border-blue-100 dark:border-blue-500/20',  'label' => 'text-blue-700 dark:text-blue-400',  'value' => 'text-blue-900 dark:text-blue-50',  'bar' => 'bg-blue-400'],
    'cyan'  => ['bg' => 'bg-cyan-50 dark:bg-cyan-500/10',  'border' => 'border-cyan-100 dark:border-cyan-500/20',  'label' => 'text-cyan-700 dark:text-cyan-400',  'value' => 'text-cyan-900 dark:text-cyan-50',  'bar' => 'bg-cyan-400'],
    'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10', 'border' => 'border-amber-100 dark:border-amber-500/20', 'label' => 'text-amber-700 dark:text-amber-400', 'value' => 'text-amber-900 dark:text-amber-50', 'bar' => 'bg-amber-400'],
];
@endphp

<div class="dashboard-grid">
    <x-ui.card class="col-span-12 overflow-hidden">

        {{-- Header --}}
        <div class="dashboard-card-header flex justify-between items-center">
            <div>
                <h3 class="dashboard-title">{{ $title }}</h3>
                @if($subtitle)
                    <p class="dashboard-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            @if(count($insights) > 0)
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1
                             rounded-full {{ $ip['bg'] }} {{ $ip['text'] }} border border-current/20">
                    <span class="material-symbols-outlined text-sm">auto_awesome</span>
                    {{ count($insights) }} insights
                </span>
            @endif
        </div>

        <div class="dashboard-card-body space-y-5">

            {{-- Stat Grid --}}
            @if(count($stats) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-{{ min(count($stats), 3) }} gap-4">
                @foreach($stats as $stat)
                @php $sp = $statPalette[$stat['color'] ?? 'blue'] ?? $statPalette['blue']; @endphp
                <div class="relative p-5 rounded-2xl {{ $sp['bg'] }} border {{ $sp['border'] }} overflow-hidden">
                    {{-- Accent bar atas --}}
                    <div class="absolute top-0 left-0 right-0 h-0.5 {{ $sp['bar'] }}"></div>
                    <p class="text-xs font-bold uppercase tracking-wider {{ $sp['label'] }} mb-2">
                        {{ $stat['label'] }}
                    </p>
                    <h3 class="font-bold {{ $sp['value'] }} leading-tight
                               {{ strlen((string)$stat['value']) > 10 ? 'text-xl' : 'text-3xl' }}">
                        {{ $stat['value'] }}
                    </h3>
                    @if(isset($stat['sub']))
                        <p class="text-xs {{ $sp['label'] }} opacity-70 mt-1">{{ $stat['sub'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- Divider --}}
            @if(count($stats) > 0 && count($insights) > 0)
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-outline-variant"></div>
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">DSS Insights</span>
                <div class="h-px flex-1 bg-outline-variant"></div>
            </div>
            @endif

            {{-- Insights Feed --}}
            @if(count($insights) > 0)
            <div class="space-y-3">
                @foreach($insights as $i => $insight)
                <div class="relative flex items-start gap-3 p-4 rounded-xl
                            border border-outline-variant bg-surface-container/30
                            hover:bg-surface-container/60 hover:shadow-sm
                            transition-all duration-200 group overflow-hidden">

                    {{-- Subtle left bar --}}
                    <div class="absolute left-0 top-0 bottom-0 w-0.5 {{ $ip['bg'] }}
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>

                    {{-- Icon --}}
                    <div class="w-9 h-9 rounded-lg {{ $ip['bg'] }} {{ $ip['text'] }}
                                flex items-center justify-center shrink-0
                                group-hover:scale-105 transition-transform duration-200">
                        <span class="material-symbols-outlined text-lg"
                              style="font-variation-settings:'FILL' 1">
                            {{ $icon }}
                        </span>
                    </div>

                    {{-- Text --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm leading-relaxed text-on-surface">
                            {{ $insight }}
                        </p>
                    </div>

                    {{-- Index --}}
                    <span class="text-[10px] font-bold text-on-surface-variant/50 shrink-0 mt-0.5">
                        #{{ $i + 1 }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-2xl text-on-surface-variant"
                              style="font-variation-settings:'FILL' 1">inbox</span>
                    </div>
                    <p class="font-semibold text-on-surface text-sm mb-1">Belum ada insight DSS</p>
                    <p class="text-xs text-on-surface-variant max-w-xs leading-relaxed">
                        Insight akan muncul setelah Financial Controller memproses prediksi DSS.
                    </p>
                </div>
            @endif

        </div>
    </x-ui.card>
</div>