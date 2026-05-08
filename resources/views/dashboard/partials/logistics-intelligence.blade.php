<div class="dashboard-grid">

    <x-ui.card class="col-span-12 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">

                Logistics Intelligence

            </h3>

            <p class="dashboard-subtitle">

                DSS-driven shipment & operational risk analysis.

            </p>

        </div>

        <div class="dashboard-card-body">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                {{-- Approval Rate --}}
                <div class="p-5 rounded-2xl bg-green-50 border border-green-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-green-700">

                        Shipment Approval Rate

                    </p>

                    <h3 class="text-3xl font-bold text-green-900 mt-2">

                        {{ $logisticsAnalytics['approval_rate'] }}%

                    </h3>

                </div>

                {{-- Risky Ship Mode --}}
                <div class="p-5 rounded-2xl bg-red-50 border border-red-100">

                    <p class="text-xs font-semibold uppercase tracking-wider text-red-700">

                        Most Risky Ship Mode

                    </p>

                    <h3 class="text-2xl font-bold text-red-900 mt-2">

                        {{ $logisticsAnalytics['risky_ship_mode'] }}

                    </h3>

                </div>

            </div>

            {{-- Insights --}}
            <div class="space-y-4">

                @foreach($logisticsInsights as $insight)

                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl border border-outline-variant bg-surface-container/30">

                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">

                            <span class="material-symbols-outlined material-icon-fill">

                                local_shipping

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