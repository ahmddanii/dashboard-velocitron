<x-ui.card class="overflow-hidden">

    <div class="dashboard-card-header">

        <h3 class="dashboard-title">
            Intelligence Feed
        </h3>

    </div>

    <div class="dashboard-card-body">

        <div class="space-y-3">

            @forelse($strategies as $strategy)

                <div class="p-4 rounded-lg border border-outline-variant">

                    <div class="flex items-start gap-3">

                        <div
                            class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">

                            <span class="material-symbols-outlined">
                                tips_and_updates
                            </span>

                        </div>

                        <div>

                            <p class="font-semibold">
                                {{ $strategy->title }}
                            </p>

                            <p class="text-sm text-on-surface-variant mt-1">
                                {{ $strategy->recommendation }}
                            </p>

                            <p class="text-xs text-slate-400 mt-2">

                                Confidence:
                                {{ $strategy->confidence }}%

                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <p class="text-sm text-slate-400">
                    Belum ada rekomendasi DSS.
                </p>

            @endforelse

        </div>

    </div>

</x-ui.card>