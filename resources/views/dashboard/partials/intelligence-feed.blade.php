<x-ui.card class="col-span-12 overflow-hidden">

    <div class="dashboard-card-header flex justify-between items-center">
        <div>
            <h3 class="dashboard-title">Intelligence Feed</h3>
            <p class="dashboard-subtitle">DSS-driven recommendations & alerts</p>
        </div>
        @if(!empty($intelligenceFeed))
            <span
                class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ count($intelligenceFeed) }} insights
            </span>
        @endif
    </div>

    <div class="dashboard-card-body">
        <div class="space-y-3">

            @forelse($intelligenceFeed as $feed)

                @php
                    // Tentukan severity berdasarkan status
                    $severity = match ($feed['status']) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'info',
                    };

                    $severityConfig = [
                        'success' => [
                            'bar' => 'bg-green-500',
                            'iconBg' => 'bg-green-100',
                            'iconText' => 'text-green-600',
                            'icon' => 'check_circle',
                            'cardBg' => 'bg-green-50/40',
                            'border' => 'border-green-100',
                            'timeBg' => 'bg-green-100 text-green-700',
                        ],
                        'danger' => [
                            'bar' => 'bg-red-500',
                            'iconBg' => 'bg-red-100',
                            'iconText' => 'text-red-600',
                            'icon' => 'warning',
                            'cardBg' => 'bg-red-50/40',
                            'border' => 'border-red-100',
                            'timeBg' => 'bg-red-100 text-red-700',
                        ],
                        'warning' => [
                            'bar' => 'bg-amber-400',
                            'iconBg' => 'bg-amber-100',
                            'iconText' => 'text-amber-600',
                            'icon' => 'schedule',
                            'cardBg' => 'bg-amber-50/40',
                            'border' => 'border-amber-100',
                            'timeBg' => 'bg-amber-100 text-amber-700',
                        ],
                        'info' => [
                            'bar' => 'bg-blue-400',
                            'iconBg' => 'bg-blue-100',
                            'iconText' => 'text-blue-600',
                            'icon' => 'info',
                            'cardBg' => 'bg-blue-50/30',
                            'border' => 'border-blue-100',
                            'timeBg' => 'bg-blue-100 text-blue-700',
                        ],
                    ];

                    $sc = $severityConfig[$severity];
                @endphp

                <div class="relative rounded-xl border {{ $sc['border'] }} {{ $sc['cardBg'] }} overflow-hidden
                                hover:shadow-sm transition-all duration-200 group">

                    {{-- Severity bar kiri --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $sc['bar'] }}"></div>

                    <div class="pl-5 pr-4 py-4 flex items-start gap-3">

                        {{-- Icon --}}
                        <div class="w-9 h-9 rounded-lg {{ $sc['iconBg'] }} {{ $sc['iconText'] }}
                                        flex items-center justify-center shrink-0
                                        group-hover:scale-105 transition-transform duration-200">
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">
                                {{ $sc['icon'] }}
                            </span>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-3 mb-1">
                                <p class="font-semibold text-sm text-on-surface leading-snug">
                                    {{ $feed['title'] }}
                                </p>
                                <div class="flex items-center gap-2 shrink-0">
                                    <x-ui.status-badge :status="$feed['status']" />
                                    <span
                                        class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $sc['timeBg'] }} whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($feed['created_at'])->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-on-surface-variant leading-relaxed">
                                {{ $feed['message'] }}
                            </p>
                        </div>

                    </div>
                </div>

            @empty

                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-3xl text-on-surface-variant"
                            style="font-variation-settings:'FILL' 1">
                            inbox
                        </span>
                    </div>
                    <p class="font-semibold text-on-surface mb-1">Belum ada rekomendasi DSS</p>
                    <p class="text-sm text-on-surface-variant max-w-xs leading-relaxed">
                        Intelligence feed akan muncul setelah Financial Controller memproses prediksi DSS.
                    </p>
                </div>

            @endforelse

        </div>
    </div>

</x-ui.card>