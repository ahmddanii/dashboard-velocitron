<div class="dashboard-grid">

    <x-ui.card class="col-span-12 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">

                Client Intelligence

            </h3>

            <p class="dashboard-subtitle">

                DSS-driven client profitability & contract analysis.

            </p>

        </div>

        <div class="dashboard-card-body">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                {{-- Approval Rate --}}
                <div class="p-5 rounded-2xl bg-green-50 border border-green-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-green-700">

                        Contract Approval Rate

                    </p>

                    <h3 class="text-3xl font-bold text-green-900 mt-2">

                        {{ $kamAnalytics['approval_rate'] }}%

                    </h3>

                </div>

                {{-- Top Segment --}}
                <div class="p-5 rounded-2xl bg-blue-50 border border-blue-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">

                        Most Profitable Segment

                    </p>

                    <h3 class="text-2xl font-bold text-blue-900 mt-2">

                        {{ $kamAnalytics['top_segment'] }}

                    </h3>

                </div>

                {{-- Top Region --}}
                <div class="p-5 rounded-2xl bg-cyan-50 border border-cyan-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-cyan-700">

                        Strongest Sales Region

                    </p>

                    <h3 class="text-2xl font-bold text-cyan-900 mt-2">

                        {{ $kamAnalytics['top_region'] }}

                    </h3>

                </div>

            </div>

            {{-- Insights --}}
            <div class="space-y-4">

                @foreach($kamInsights as $insight)

                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl border border-outline-variant bg-surface-container/30">

                        <div
                            class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">

                            <span class="material-symbols-outlined material-icon-fill">

                                handshake

                            </span>

                        </div>

                        <p class="text-sm leading-relaxed text-on-surface">

                            {{ $insight }}

                        </p>

                    </div>

                @endforeach

            </div>

        </div>

    </x-ui.card>

</div>