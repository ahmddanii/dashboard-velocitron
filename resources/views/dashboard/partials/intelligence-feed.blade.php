<x-ui.card class="col-span-12 overflow-hidden">

    <div class="dashboard-card-header">

        <h3 class="dashboard-title">
            Intelligence Feed
        </h3>

    </div>

    <div class="dashboard-card-body">

        <div class="space-y-4">

            @forelse($intelligenceFeed as $feed)

                    <div class="p-4 rounded-xl border border-outline-variant">

                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-full flex items-center justify-center

                                    {{ $feed['status'] === 'approved'
                ? 'bg-green-100 text-green-600'
                : 'bg-red-100 text-red-600' }}">

                                <span class="material-symbols-outlined material-icon-fill">

                                    {{ $feed['status'] === 'approved'
                ? 'check_circle'
                : 'warning' }}

                                </span>

                            </div>

                            <div class="flex-1">

                                <div class="flex justify-between items-start gap-4">

                                    <div>

                                        <p class="font-semibold">

                                            {{ $feed['title'] }}

                                        </p>

                                        <p class="text-sm text-on-surface-variant mt-1 leading-relaxed">

                                            {{ $feed['message'] }}

                                        </p>

                                    </div>

                                    <span class="text-xs text-on-surface-variant whitespace-nowrap">

                                        {{ \Carbon\Carbon::parse($feed['created_at'])->diffForHumans() }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

            @empty

                <div class="text-center py-8 text-on-surface-variant">

                    Belum ada rekomendasi DSS.

                </div>

            @endforelse

        </div>

    </div>

</x-ui.card>