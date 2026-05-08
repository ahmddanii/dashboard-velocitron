@include('dashboard.partials.analytics-monitoring')

<div class="flex justify-end mb-6">
    <a href="{{ route('requests.pending') }}" class="inline-flex items-center gap-2
        px-4 py-2 rounded-lg
        bg-secondary text-white
        text-sm font-semibold">

        <span class="material-symbols-outlined">
            pending_actions
        </span>

        Pending Requests

    </a>
</div>

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 lg:col-span-8 overflow-hidden">

        <div class="dashboard-card-header flex justify-between items-center">

            <div>
                <h3 class="dashboard-title">
                    Tren Sales & Profit Bulanan
                </h3>

                <p class="dashboard-subtitle">
                    Performa penjualan dan profit bulanan
                </p>
            </div>

            <div class="flex gap-4">

                <div class="flex items-center gap-2">

                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>

                    <span class="text-xs font-semibold text-on-surface-variant">
                        Sales
                    </span>

                </div>

                <div class="flex items-center gap-2">

                    <span class="w-3 h-3 rounded-full bg-green-500"></span>

                    <span class="text-xs font-semibold text-on-surface-variant">
                        Profit
                    </span>

                </div>

            </div>

        </div>

        <div class="dashboard-card-body h-72">
            <canvas id="monthlyChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-4 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Sales per Region
            </h3>

        </div>

        <div class="dashboard-card-body">

            <div class="h-48 flex items-center justify-center">
                <canvas id="regionChart"></canvas>
            </div>

            <div class="mt-3 space-y-2">

                @foreach($region as $i => $r)

                    @php
                        $colors = [
                            'bg-blue-600',
                            'bg-green-500',
                            'bg-amber-400',
                            'bg-red-400'
                        ];
                    @endphp

                    <div class="flex justify-between items-center text-sm">

                        <div class="flex items-center gap-2">

                            <span class="w-2.5 h-2.5 rounded-full {{ $colors[$i] ?? 'bg-slate-400' }}">
                            </span>

                            <span class="text-on-surface-variant">
                                {{ $r['region'] }}
                            </span>

                        </div>

                        <span class="font-semibold">
                            ${{ number_format($r['total_sales'], 0) }}
                        </span>

                    </div>

                @endforeach

            </div>

        </div>

    </x-ui.card>

</div>

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-4 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Sales per Tahun
            </h3>

        </div>

        <div class="dashboard-card-body h-56">
            <canvas id="yearlyChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-4 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Profit per Kategori
            </h3>

        </div>

        <div class="dashboard-card-body h-56">
            <canvas id="categoryChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-4 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Sales per Segmen
            </h3>

        </div>

        <div class="dashboard-card-body h-56">
            <canvas id="segmentChart"></canvas>
        </div>

    </x-ui.card>

</div>

@include('dashboard.partials.top-products')