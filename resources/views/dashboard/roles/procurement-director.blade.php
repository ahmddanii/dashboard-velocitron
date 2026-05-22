{{-- Procurement Executive Dashboard --}}
<div class="space-y-6">
    {{-- 1. Primary Analysis Grid --}}
    <div class="grid grid-cols-12 gap-6 items-stretch">
        {{-- Profit Analysis Chart & Details --}}
        <x-ui.card class="col-span-12 lg:col-span-8 overflow-hidden flex flex-col">
            <div class="dashboard-card-header border-b border-outline-variant/50 bg-surface-container-lowest/50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-500/10 flex items-center justify-center text-green-600 dark:text-green-400 border border-green-100 dark:border-green-500/20 shadow-sm">
                        <span class="material-symbols-outlined text-xl">payments</span>
                    </div>
                    <div>
                        <h3 class="dashboard-title">Profit Analysis: Technology & Furniture</h3>
                        <p class="dashboard-subtitle">Monitoring margin performance across managed categories.</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-12 gap-0 flex-1">
                {{-- Chart Area --}}
                <div class="col-span-12 md:col-span-7 p-8 border-r border-outline-variant/30 flex flex-col">
                    <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-6">Revenue vs Profit Trend</p>
                    <div class="flex-1 relative min-h-[300px]">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                
                {{-- Category Detail List --}}
                <div class="col-span-12 md:col-span-5 p-8 space-y-4 bg-surface-container-lowest/30 flex flex-col justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4">Category Performance</p>
                        <div class="space-y-4">
                            @foreach($category as $cat)
                                <div class="group p-4 bg-surface-container-lowest/50 dark:bg-white/5 backdrop-blur-sm border border-outline-variant rounded-2xl hover:border-primary/50 hover:shadow-xl hover:shadow-primary/5 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 rounded-xl {{ $cat['category'] === 'Technology' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400' : ($cat['category'] === 'Furniture' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400') }}">
                                                <span class="material-symbols-outlined text-lg">
                                                    {{ $cat['category'] === 'Technology' ? 'memory' : ($cat['category'] === 'Furniture' ? 'chair' : 'inventory_2') }}
                                                </span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-on-surface truncate">{{ $cat['category'] }}</p>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="text-[10px] font-bold text-on-surface-variant">MARGIN:</span>
                                                    <span class="text-[10px] font-black {{ ($cat['avg_margin'] ?? 0) > 10 ? 'text-green-600' : 'text-amber-600' }}">
                                                        {{ $cat['avg_margin'] ?? '-' }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter mb-0.5">Net Profit</p>
                                            <p class="text-base font-black text-green-600">${{ number_format($cat['total_profit'], 0) }}</p>
                                        </div>
                                    </div>
                                    
                                    {{-- Mini Progress Bar with Glow --}}
                                    <div class="h-1.5 w-full bg-surface-container rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full rounded-full transition-all duration-1000 {{ $cat['category'] === 'Technology' ? 'bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]' : ($cat['category'] === 'Furniture' ? 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]' : 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]') }}" 
                                             style="width: {{ min(($cat['total_profit'] / ($cat['total_sales'] ?: 1)) * 300, 100) }}%"></div>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-between items-center">
                                        <div class="flex items-center gap-1 text-[10px] text-on-surface-variant font-bold">
                                            <span class="material-symbols-outlined text-[12px]">bar_chart</span>
                                            VOL: ${{ number_format($cat['total_sales'], 0) }}
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] text-green-600 font-black">
                                            <span class="material-symbols-outlined text-[12px]">trending_up</span>
                                            +12.4%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Summary Insight at the bottom --}}
                    <div class="mt-4 p-5 rounded-2xl bg-blue-50/50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                <span class="material-symbols-outlined text-xl">insights</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Executive Note</p>
                                <p class="text-[13px] text-on-surface-variant dark:text-on-surface leading-relaxed">
                                    Fokus pengadaan pada <span class="font-black text-on-surface underline decoration-blue-500/30 underline-offset-4">Technology</span> untuk memaksimalkan ROI kuartal ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Procurement Health --}}
        <x-ui.card class="col-span-12 lg:col-span-4 overflow-hidden flex flex-col">
            <div class="dashboard-card-header bg-secondary/5 border-b border-outline-variant/30 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-base">health_and_safety</span>
                    <h3 class="dashboard-title">Procurement Health</h3>
                </div>
                <p class="dashboard-subtitle">Approval volume & AI confidence.</p>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div class="space-y-6">
                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-green-50 dark:bg-green-500/10 border border-green-100 dark:border-green-500/20">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                                <span class="text-[10px] font-black text-green-700 uppercase tracking-widest">Approved</span>
                            </div>
                            <p class="text-2xl font-black text-green-700 dark:text-green-400">{{ $procurementAnalytics['approved_procurement'] }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-red-600 text-sm">cancel</span>
                                <span class="text-[10px] font-black text-red-700 uppercase tracking-widest">Rejected</span>
                            </div>
                            <p class="text-2xl font-black text-red-700 dark:text-red-400">{{ $procurementAnalytics['rejected_procurement'] }}</p>
                        </div>
                    </div>

                    {{-- Confidence Meter --}}
                    <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider">AI Confidence Score</h4>
                            <span class="text-lg font-mono font-black text-secondary">{{ $procurementAnalytics['avg_confidence'] }}%</span>
                        </div>
                        <div class="h-3 w-full bg-surface-container rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-secondary/60 to-secondary transition-all duration-1000" 
                                 style="width: {{ $procurementAnalytics['avg_confidence'] }}%"></div>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-2 italic">Based on latest 50 procurement requests.</p>
                    </div>

                    {{-- Detailed Insights --}}
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Strategy Insights</p>
                        @foreach(array_slice($procurementInsights, 1, 2) as $insight)
                            <div class="flex items-start gap-3 p-3 rounded-xl bg-surface-container-lowest border border-outline-variant/50">
                                <span class="material-symbols-outlined text-primary text-sm mt-0.5">lightbulb</span>
                                <p class="text-xs text-on-surface-variant leading-relaxed">{{ $insight }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="mt-8 pt-4 border-t border-outline-variant/30">
                    <div class="flex items-center gap-2 text-[10px] text-on-surface-variant font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        System monitoring active: {{ now()->format('H:i') }}
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- 3. Top Products Table --}}
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @include('dashboard.partials.top-products')
        </div>
    </div>

    {{-- 4. Intelligence & Strategy Row --}}
    <div class="grid grid-cols-12 gap-6 items-stretch">
        {{-- DSS Strategic Recommendation Card --}}
        <div class="col-span-12 lg:col-span-5">
            <x-ui.card class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white overflow-hidden relative border-none h-full">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl"></div>
                
                <div class="p-8 relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-lg border border-white/10">
                                <span class="material-symbols-outlined text-4xl">bolt</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-black">DSS Strategic Insight</h4>
                                <p class="text-[10px] text-blue-100 uppercase tracking-widest font-black opacity-80">AI RECOMMENDATIONS</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="p-6 rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-inner">
                                <span class="material-symbols-outlined text-blue-200 text-xl mb-2">format_quote</span>
                                <p class="text-base text-blue-50 leading-relaxed italic">
                                    "{{ $procurementInsights[0] ?? 'Optimalkan pengadaan pada kategori Technology untuk meningkatkan margin keuntungan.' }}"
                                </p>
                            </div>

                            <div class="p-6 rounded-3xl bg-black/10 backdrop-blur-sm border border-white/10">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="material-symbols-outlined text-amber-300 text-sm">tips_and_updates</span>
                                    <p class="text-xs font-black uppercase tracking-widest text-blue-100">Pro-Tip Strategy</p>
                                </div>
                                <p class="text-sm text-blue-50 leading-relaxed">
                                    Monitor margin harian dan gunakan rekomendasi harga beli maksimal dari Financial Controller untuk memastikan profitabilitas tetap terjaga.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-white/10 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Priority: High</span>
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-indigo-700"></div>
                            <div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-indigo-700"></div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        {{-- Intelligence Feed --}}
        <div class="col-span-12 lg:col-span-7 h-full">
            @include('dashboard.partials.intelligence-feed', ['colSpan' => 'h-full'])
        </div>
    </div>
</div>