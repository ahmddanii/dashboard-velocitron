@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="p-6" x-data="{
        showAnalyticsPreview: false,
        analyticsMetrics: {},
        recentPredictions: [],
        isLoadingAnalytics: false,
        fetchAnalyticsPreview() {
            this.isLoadingAnalytics = true;
            this.showAnalyticsPreview = true;
            fetch('{{ route('analytics.export.preview') }}')
                .then(res => res.json())
                .then(data => {
                    this.analyticsMetrics = data.metrics;
                    this.recentPredictions = data.recent;
                    this.isLoadingAnalytics = false;
                });
        }
    }">

        <div class="max-w-[1440px] mx-auto">

            {{-- Error: Flask tidak jalan --}}
            @if(isset($apiError))

                @include('dashboard.partials.api-error')

            @else
                <script type="application/json" id="dashboard-context">
                    @json($dashboardData)
                </script>

                @include('dashboard.partials.dashboard-header')

                {{-- FILTER BAR --}}
                <form method="GET" class="flex flex-wrap gap-3 mb-6">

                    {{-- Status Filter --}}
                    <x-ui.filter-select name="status" :selected="request('status')" :options="[

                    '' => 'All Status',

                    'approved' => 'Approved',

                    'rejected' => 'Rejected',
                ]" />

                    {{-- Period Filter --}}
                    <x-ui.filter-select name="period" :selected="request('period')" :options="[

                    '' => 'All Time',

                    '7days' => 'Last 7 Days',

                    '30days' => 'Last 30 Days',

                    'year' => 'This Year',
                ]" />

                </form>

                {{-- KPI CARDS --}}
                @include('dashboard.partials.kpi-cards')

                @includeIf("dashboard.roles.$role")

            @endif

            {{-- Analytics Export Preview Modal --}}
            <div x-show="showAnalyticsPreview" 
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-cloak>
                
                <div class="bg-surface rounded-[32px] shadow-2xl max-w-2xl w-full overflow-hidden border border-outline-variant">
                    {{-- Header --}}
                    <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-inner">
                                <span class="material-symbols-outlined text-2xl">analytics</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-on-surface">DSS Report Summary</h3>
                                <p class="text-xs text-on-surface-variant">Pratinjau metrik keputusan AI sebelum dieksport.</p>
                            </div>
                        </div>
                        <button @click="showAnalyticsPreview = false" class="p-2 hover:bg-surface-container rounded-full transition">
                            <span class="material-symbols-outlined text-on-surface-variant">close</span>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-8">
                        <template x-if="isLoadingAnalytics">
                            <div class="flex flex-col items-center justify-center py-12 gap-4">
                                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-on-surface-variant animate-pulse font-medium text-sm">Menghitung metrik terbaru...</p>
                            </div>
                        </template>

                        <template x-if="!isLoadingAnalytics">
                            <div class="space-y-6">
                                {{-- Metric Cards --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Predictions</p>
                                        <h4 class="text-2xl font-bold text-on-surface" x-text="analyticsMetrics.total"></h4>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Avg Confidence</p>
                                        <h4 class="text-2xl font-bold text-blue-600" x-text="analyticsMetrics.avg_confidence"></h4>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Detail Keputusan</p>
                                    <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden flex">
                                        <div class="bg-green-500 h-full transition-all duration-1000" 
                                            :style="`width: ${(analyticsMetrics.approved / analyticsMetrics.total) * 100}%`"></div>
                                        <div class="bg-red-500 h-full transition-all duration-1000"
                                            :style="`width: ${(analyticsMetrics.rejected / analyticsMetrics.total) * 100}%`"></div>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            <span class="text-on-surface-variant">Approved: <span class="font-bold text-on-surface" x-text="analyticsMetrics.approved"></span></span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                            <span class="text-on-surface-variant">Rejected: <span class="font-bold text-on-surface" x-text="analyticsMetrics.rejected"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="px-8 py-6 bg-surface-container-low border-t border-outline-variant flex justify-end gap-3">
                        <button @click="showAnalyticsPreview = false" 
                            class="px-6 py-2.5 rounded-2xl border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition-all">
                            Batal
                        </button>
                        <a href="{{ route('analytics.export') }}" 
                            @click="showAnalyticsPreview = false"
                            class="inline-flex items-center gap-2 px-8 py-2.5 rounded-2xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            <span class="material-symbols-outlined text-base">download</span>
                            Download Full CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection