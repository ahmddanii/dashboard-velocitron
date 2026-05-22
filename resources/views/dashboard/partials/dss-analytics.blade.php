<div class="dashboard-grid">

    <x-ui.card class="col-span-12 overflow-hidden">

        <div class="dashboard-card-header flex justify-between items-center">
            <div>
                <h3 class="dashboard-title">
                    DSS Executive Analytics
                </h3>
                <p class="dashboard-subtitle">
                    Historical intelligence from DSS decisions.
                </p>
            </div>

            @if(auth()->user()->hasRole('head-analytics'))
            <button @click="fetchAnalyticsPreview()" 
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-500/40 hover:shadow-blue-500/60 hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined text-base">download</span>
                Export Report
            </button>
            @endif
        </div>

        <div class="dashboard-card-body">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                {{-- Approval Rate --}}
                <div class="p-6 rounded-3xl bg-green-50/40 dark:bg-green-500/5 backdrop-blur-md border border-green-200/50 dark:border-green-500/10 hover:bg-green-50/60 dark:hover:bg-green-500/10 transition-all duration-300 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-green-700 dark:text-green-400">
                        Approval Rate
                    </p>
                    <h3 class="text-3xl font-black text-green-800 dark:text-green-300 mt-2">
                        {{ $dssAnalytics['approval_rate'] ?? 0 }}%
                    </h3>
                </div>

                {{-- Rejection Rate --}}
                <div class="p-6 rounded-3xl bg-red-50/40 dark:bg-red-500/5 backdrop-blur-md border border-red-200/50 dark:border-red-500/10 hover:bg-red-50/60 dark:hover:bg-red-500/10 transition-all duration-300 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-700 dark:text-red-400">
                        Rejection Rate
                    </p>
                    <h3 class="text-3xl font-black text-red-800 dark:text-red-300 mt-2">
                        {{ $dssAnalytics['rejection_rate'] ?? 0 }}%
                    </h3>
                </div>

                {{-- Avg Confidence --}}
                <div class="p-6 rounded-3xl bg-blue-50/40 dark:bg-blue-500/5 backdrop-blur-md border border-blue-200/50 dark:border-blue-500/10 hover:bg-blue-50/60 dark:hover:bg-blue-500/10 transition-all duration-300 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-700 dark:text-blue-400">
                        Avg Confidence
                    </p>
                    <h3 class="text-3xl font-black text-blue-800 dark:text-blue-300 mt-2">
                        {{ $dssAnalytics['avg_confidence'] ?? 0 }}%
                    </h3>
                </div>

                {{-- Risky Category --}}
                <div class="p-6 rounded-3xl bg-amber-50/40 dark:bg-amber-500/5 backdrop-blur-md border border-amber-200/50 dark:border-amber-500/10 hover:bg-amber-50/60 dark:hover:bg-amber-500/10 transition-all duration-300 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-700 dark:text-amber-400">
                        Most Risky Category
                    </p>
                    <h3 class="text-xl font-black text-amber-800 dark:text-amber-300 mt-2">
                        {{ $dssAnalytics['risky_category'] ?? '-' }}
                    </h3>
                </div>

                {{-- Risky Ship Mode --}}
                <div class="p-6 rounded-3xl bg-purple-50/40 dark:bg-purple-500/5 backdrop-blur-md border border-purple-200/50 dark:border-purple-500/10 hover:bg-purple-50/60 dark:hover:bg-purple-500/10 transition-all duration-300 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-purple-700 dark:text-purple-400">
                        Risky Ship Mode
                    </p>
                    <h3 class="text-xl font-black text-purple-800 dark:text-purple-300 mt-2">
                        {{ $dssAnalytics['risky_ship_mode'] ?? '-' }}
                    </h3>
                </div>

            </div>

        </div>

    </x-ui.card>

</div>