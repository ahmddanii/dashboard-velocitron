<div class="dashboard-grid">
    <x-ui.card class="col-span-12 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">

                Executive DSS Insights

            </h3>

            <p class="dashboard-subtitle">

                AI-driven narrative interpretation from DSS analytics.

            </p>

        </div>

        <div class="dashboard-card-body">

            <div class="space-y-4">

                @foreach($executiveInsights as $insight)

                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl border border-outline-variant bg-surface-container/30">

                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">

                            <span class="material-symbols-outlined material-icon-fill">

                                insights

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