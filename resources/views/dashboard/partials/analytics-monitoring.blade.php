<div class="dashboard-grid">
    <x-ui.card class="col-span-12 overflow-hidden">

        {{-- Header --}}
        <div class="dashboard-card-header flex justify-between items-center">
            <div>
                <h3 class="dashboard-title">DSS Monitoring Center</h3>
                <p class="dashboard-subtitle">Real-time monitoring for DSS performance & prediction stability.</p>
            </div>
            <a href="{{ route('analytics.export') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-purple-600 text-white text-sm font-semibold
                      hover:bg-purple-700 transition shrink-0">
                <span class="material-symbols-outlined text-base">download</span>
                Export DSS Report
            </a>
        </div>

        <div class="dashboard-card-body space-y-6">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Prediction Volume --}}
                <div class="relative p-5 rounded-2xl bg-blue-50 border border-blue-100 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-blue-400"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                        Prediction Volume
                    </p>
                    <h3 class="text-3xl font-bold text-blue-900">
                        {{ number_format($analyticsMonitoring['prediction_volume'] ?? 0) }}
                    </h3>
                    <p class="text-xs text-blue-600 mt-1">Total DSS requests</p>
                </div>

                {{-- Avg Confidence --}}
                <div class="relative p-5 rounded-2xl bg-green-50 border border-green-100 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-green-400"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-green-700 mb-2">
                        Avg Confidence
                    </p>
                    <h3 class="text-3xl font-bold text-green-900">
                        {{ $analyticsMonitoring['avg_confidence'] ?? 0 }}%
                    </h3>
                    <p class="text-xs mt-1
                        {{ ($analyticsMonitoring['avg_confidence'] ?? 0) >= 75 ? 'text-green-600' : 'text-amber-600' }}">
                        {{ ($analyticsMonitoring['avg_confidence'] ?? 0) >= 75 ? '✓ Model stabil' : '⚠ Perlu monitoring' }}
                    </p>
                </div>

                {{-- Prediction Accuracy --}}
                {{-- ✅ Fix: pakai 'prediction_accuracy' bukan 'estimated_accuracy' --}}
                <div class="relative p-5 rounded-2xl bg-cyan-50 border border-cyan-100 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-cyan-400"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-2">
                        Prediction Accuracy
                    </p>
                    <h3 class="text-3xl font-bold text-cyan-900">
                        {{ $analyticsMonitoring['prediction_accuracy'] ?? 0 }}%
                    </h3>
                    <p class="text-xs text-cyan-600 mt-1">Profitable vs total</p>
                </div>

                {{-- DSS Health --}}
                {{-- ✅ Fix: health_status dihitung dari avg_confidence --}}
                @php
                    $avgConf    = $analyticsMonitoring['avg_confidence'] ?? 0;
                    $health     = $avgConf >= 75 ? 'Stable' : 'Monitoring Required';
                    $healthBg   = $avgConf >= 75 ? 'bg-amber-50 border-amber-100' : 'bg-red-50 border-red-100';
                    $healthBar  = $avgConf >= 75 ? 'bg-amber-400' : 'bg-red-400';
                    $healthText = $avgConf >= 75 ? 'text-amber-700' : 'text-red-700';
                    $healthVal  = $avgConf >= 75 ? 'text-amber-900' : 'text-red-900';
                    $healthSub  = $avgConf >= 75 ? 'text-amber-600' : 'text-red-500';
                @endphp
                <div class="relative p-5 rounded-2xl {{ $healthBg }} border overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 {{ $healthBar }}"></div>
                    <p class="text-xs font-bold uppercase tracking-wider {{ $healthText }} mb-2">
                        DSS Health
                    </p>
                    <h3 class="text-2xl font-bold {{ $healthVal }}">
                        {{ $health }}
                    </h3>
                    <p class="text-xs {{ $healthSub }} mt-1">
                        Confidence: {{ $avgConf }}%
                    </p>
                </div>

            </div>

            {{-- Profitable vs Risky breakdown --}}
            @php
                $total      = $analyticsMonitoring['prediction_volume'] ?? 0;
                $profitable = $analyticsMonitoring['profitable_predictions'] ?? 0;
                $risky      = $analyticsMonitoring['risky_predictions'] ?? 0;
                $profitPct  = $total > 0 ? round(($profitable / $total) * 100) : 0;
                $riskyPct   = $total > 0 ? round(($risky / $total) * 100) : 0;
            @endphp
            @if($total > 0)
            <div class="bg-surface-container-low rounded-xl p-4">
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3">
                    Prediction Distribution
                </p>
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex-1 h-3 bg-slate-200 rounded-full overflow-hidden flex">
                        <div class="h-full bg-green-500 rounded-full transition-all"
                             style="width: {{ $profitPct }}%"></div>
                        <div class="h-full bg-red-400 transition-all"
                             style="width: {{ $riskyPct }}%"></div>
                    </div>
                </div>
                <div class="flex gap-6 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        <span class="text-on-surface-variant">Profitable:</span>
                        <span class="font-bold text-green-700">{{ $profitable }} ({{ $profitPct }}%)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="text-on-surface-variant">Loss/Risk:</span>
                        <span class="font-bold text-red-600">{{ $risky }} ({{ $riskyPct }}%)</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Insights --}}
            {{-- ✅ Fix: pakai 'executiveInsights' bukan 'analyticsInsights' --}}
            @if(!empty($executiveInsights))
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-px flex-1 bg-outline-variant"></div>
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">
                        DSS Insights
                    </span>
                    <div class="h-px flex-1 bg-outline-variant"></div>
                </div>
                <div class="space-y-3">
                    @foreach($executiveInsights as $i => $insight)
                    <div class="relative flex items-start gap-3 p-4 rounded-xl
                                border border-outline-variant bg-surface-container/30
                                hover:bg-surface-container/60 transition-all duration-200 group overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-purple-300
                                    opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600
                                    flex items-center justify-center shrink-0
                                    group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-lg"
                                  style="font-variation-settings:'FILL' 1">monitoring</span>
                        </div>
                        <p class="text-sm leading-relaxed text-on-surface flex-1">{{ $insight }}</p>
                        <span class="text-[10px] font-bold text-on-surface-variant/40 shrink-0 mt-0.5">
                            #{{ $i + 1 }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </x-ui.card>
</div>