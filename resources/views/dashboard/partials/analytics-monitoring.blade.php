<div class="dashboard-grid">

    <x-ui.card class="col-span-12 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">

                DSS Monitoring Center

            </h3>

            <p class="dashboard-subtitle">

                Real-time monitoring for DSS performance & prediction stability.

            </p>

        </div>

        <div class="flex justify-end mb-4">

            <a href="{{ route('analytics.export') }}" class="inline-flex items-center gap-2
        px-4 py-2 rounded-xl
        bg-purple-600 text-white
        text-sm font-semibold
        hover:bg-purple-700 transition">

                <span class="material-symbols-outlined">

                    download

                </span>

                Export DSS Report

            </a>

        </div>

        <div class="dashboard-card-body">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                {{-- Prediction Volume --}}
                <div class="p-5 rounded-2xl bg-blue-50 border border-blue-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">

                        Prediction Volume

                    </p>

                    <h3 class="text-3xl font-bold text-blue-900 mt-2">

                        {{ number_format($analyticsMonitoring['prediction_volume']) }}

                    </h3>

                </div>

                {{-- Avg Confidence --}}
                <div class="p-5 rounded-2xl bg-green-50 border border-green-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-green-700">

                        Avg Confidence

                    </p>

                    <h3 class="text-3xl font-bold text-green-900 mt-2">

                        {{ $analyticsMonitoring['avg_confidence'] }}%

                    </h3>

                </div>

                {{-- Estimated Accuracy --}}
                <div class="p-5 rounded-2xl bg-cyan-50 border border-cyan-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-cyan-700">

                        Estimated Accuracy

                    </p>

                    <h3 class="text-3xl font-bold text-cyan-900 mt-2">

                        {{ $analyticsMonitoring['estimated_accuracy'] }}%

                    </h3>

                </div>

                {{-- Health --}}
                <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">

                        DSS Health

                    </p>

                    <h3 class="text-2xl font-bold text-amber-900 mt-2">

                        {{ $analyticsMonitoring['health_status'] }}

                    </h3>

                </div>

            </div>

            {{-- Insights --}}
            <div class="space-y-4">

                @foreach($analyticsInsights as $insight)

                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl border border-outline-variant bg-surface-container/30">

                        <div
                            class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">

                            <span class="material-symbols-outlined material-icon-fill">

                                monitoring

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