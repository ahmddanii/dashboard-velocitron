<div class="dashboard-grid">

    <x-ui.card class="col-span-12 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">

                DSS Executive Analytics

            </h3>

            <p class="dashboard-subtitle">

                Historical intelligence from DSS decisions.

            </p>

        </div>

        <div class="dashboard-card-body">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                {{-- Approval Rate --}}
                <div class="p-5 rounded-2xl bg-green-50 border border-green-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-green-700">

                        Approval Rate

                    </p>

                    <h3 class="text-3xl font-bold text-green-900 mt-2">

                        {{ $dssAnalytics['approval_rate'] }}%

                    </h3>

                </div>

                {{-- Rejection Rate --}}
                <div class="p-5 rounded-2xl bg-red-50 border border-red-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-red-700">

                        Rejection Rate

                    </p>

                    <h3 class="text-3xl font-bold text-red-900 mt-2">

                        {{ $dssAnalytics['rejection_rate'] }}%

                    </h3>

                </div>

                {{-- Avg Confidence --}}
                <div class="p-5 rounded-2xl bg-blue-50 border border-blue-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">

                        Avg Confidence

                    </p>

                    <h3 class="text-3xl font-bold text-blue-900 mt-2">

                        {{ $dssAnalytics['avg_confidence'] }}%

                    </h3>

                </div>

                {{-- Risky Category --}}
                <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">

                        Most Risky Category

                    </p>

                    <h3 class="text-xl font-bold text-amber-900 mt-2">

                        {{ $dssAnalytics['risky_category'] }}

                    </h3>

                </div>

                {{-- Risky Ship Mode --}}
                <div class="p-5 rounded-2xl bg-purple-50 border border-purple-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-purple-700">

                        Risky Ship Mode

                    </p>

                    <h3 class="text-xl font-bold text-purple-900 mt-2">

                        {{ $dssAnalytics['risky_ship_mode'] }}

                    </h3>

                </div>

            </div>

        </div>

    </x-ui.card>

</div>