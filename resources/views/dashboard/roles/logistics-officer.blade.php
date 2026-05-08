@include('dashboard.partials.logistics-intelligence')

<div class="flex justify-end mb-6">

    <a href="{{ route('requests.create') }}" class="inline-flex items-center gap-2
        px-4 py-2 rounded-lg
        bg-secondary text-white
        text-sm font-semibold hover:opacity-90 transition">

        <span class="material-symbols-outlined">
            add
        </span>

        Create Request

    </a>
</div>

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-7 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Distribusi Orders per Region
            </h3>

        </div>

        <div class="dashboard-card-body h-64">
            <canvas id="regionBarChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-5 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Region Breakdown
            </h3>

        </div>

        <div class="dashboard-card-body">
            <div class="space-y-4">

                @foreach($region as $r)
                    @php
                        $total = collect($region)->sum('total_sales');

                        $pct = $total > 0
                            ? round(($r['total_sales'] / $total) * 100, 1)
                            : 0;
                    @endphp

                    <div>

                        <div class="flex justify-between text-sm mb-1">

                            <span class="font-semibold">
                                {{ $r['region'] }}
                            </span>

                            <span class="text-on-surface-variant">
                                {{ $pct }}%
                            </span>

                        </div>

                        <div class="h-2 bg-surface-container rounded-full overflow-hidden">

                            <div class="h-full bg-secondary rounded-full" style="width: {{ $pct }}%">
                            </div>

                        </div>

                        <p class="text-xs text-on-surface-variant mt-1">

                            {{ number_format($r['total_orders']) }}
                            orders ·

                            ${{ number_format($r['total_profit'], 0) }}
                            profit

                        </p>

                    </div>

                @endforeach

            </div>

        </div>

    </x-ui.card>

</div>

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-5 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Volume per Tahun
            </h3>

        </div>

        <div class="dashboard-card-body h-56">
            <canvas id="yearlyChart"></canvas>
        </div>

    </x-ui.card>

    {{-- Intellegence Feed --}}
    @include('dashboard.partials.intelligence-feed')

</div>