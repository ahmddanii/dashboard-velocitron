@include('dashboard.partials.logistics-intelligence')

<div class="dashboard-grid">
    {{-- Baris 1: Analisis Utama (7-5) --}}
    <x-ui.card class="col-span-12 lg:col-span-7 overflow-hidden flex flex-col">
        <div class="dashboard-card-header">
            <h3 class="dashboard-title">Regional Order Analysis</h3>
        </div>
        <div class="dashboard-card-body flex-1 p-6">
            <div class="h-72">
                <canvas id="regionBarChart"></canvas>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card class="col-span-12 lg:col-span-5 overflow-hidden flex flex-col">
        <div class="dashboard-card-header">
            <h3 class="dashboard-title">Market Share Breakdown</h3>
        </div>
        <div class="dashboard-card-body flex-1 p-6 flex flex-col justify-center">
            <div class="space-y-6">
                @foreach ($region as $r)
                    @php
                        $total = collect($region)->sum('total_sales');
                        $pct = $total > 0 ? round(($r['total_sales'] / $total) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-bold text-on-surface">{{ $r['region'] }}</span>
                            <span class="text-sm font-black text-secondary">{{ $pct }}%</span>
                        </div>
                        <div class="h-2.5 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-secondary rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-on-surface-variant font-medium">
                            <span>{{ number_format($r['total_orders']) }} orders</span>
                            <span class="text-green-600 font-bold">${{ number_format($r['total_profit'], 0) }} profit</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-ui.card>
</div>

<div class="dashboard-grid mt-6">
    {{-- Baris 2: Tren & Feed (5-7) --}}
    <x-ui.card class="col-span-12 lg:col-span-5 overflow-hidden flex flex-col">
        <div class="dashboard-card-header">
            <h3 class="dashboard-title">Yearly Volume Trend</h3>
        </div>
        <div class="dashboard-card-body flex-1 p-6 flex flex-col justify-center h-[400px]">
            <div class="h-64">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </x-ui.card>

    @include('dashboard.partials.intelligence-feed', ['colSpan' => 'col-span-12 lg:col-span-7'])
</div>
