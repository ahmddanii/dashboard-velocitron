@extends('layouts.app')

@section('title', 'Dashboard BI Superstore')

@section('content')
    <div class="p-6">
        <div class="max-w-[1440px] mx-auto">

            {{-- ── Error: Flask tidak jalan ─────────────────────── --}}
            @if(isset($apiError))
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                        <span class="material-symbols-outlined text-2xl">cloud_off</span>
                    </div>
                    <div>
                        <p class="font-semibold text-red-800 text-base">Flask API tidak dapat dihubungi</p>
                        <p class="text-red-600 text-sm mt-1">Pastikan Flask sudah berjalan dengan perintah berikut di terminal:
                        </p>
                        <code
                            class="block mt-2 bg-red-100 text-red-800 text-xs px-3 py-2 rounded font-mono">cd path/ke/folder/backend &amp;&amp; python app.py</code>
                    </div>
                </div>
            @else

                {{-- ── Page Header ───────────────────────────────────── --}}
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="font-display-lg text-display-lg text-on-surface">Superstore BI Dashboard</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                            Analisis penjualan, profit, dan performa bisnis secara menyeluruh.
                        </p>
                    </div>
                    <a href="{{ route('dashboard.dss') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all">
                        <span class="material-symbols-outlined text-sm">psychology</span>
                        Prediksi DSS
                    </a>
                </div>

                {{-- ── KPI Cards ─────────────────────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                    {{-- Total Sales --}}
                    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Sales</p>
                            <p class="font-headline-md text-headline-md">${{ number_format($summary['total_sales'], 0) }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Semua periode</p>
                        </div>
                    </div>

                    {{-- Total Profit --}}
                    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600 shrink-0">
                            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">trending_up</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Profit</p>
                            <p class="font-headline-md text-headline-md">${{ number_format($summary['total_profit'], 0) }}</p>
                            <p class="text-xs text-green-600 mt-0.5">Keuntungan bersih</p>
                        </div>
                    </div>

                    {{-- Total Orders --}}
                    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                            <span class="material-symbols-outlined"
                                style="font-variation-settings:'FILL' 1">shopping_cart</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Orders</p>
                            <p class="font-headline-md text-headline-md">{{ number_format($summary['total_orders']) }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Order unik</p>
                        </div>
                    </div>

                    {{-- Avg Profit Margin --}}
                    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">percent</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Avg Profit Margin</p>
                            <p class="font-headline-md text-headline-md">{{ $summary['avg_profit_pct'] }}%</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Rata-rata margin</p>
                        </div>
                    </div>
                </div>

                {{-- ── Charts Row 1 ──────────────────────────────────── --}}
                <div class="grid grid-cols-12 gap-6 mb-6">

                    {{-- Tren Bulanan (line chart - lebar) --}}
                    <div class="col-span-12 lg:col-span-8 bg-white border border-outline-variant rounded-xl overflow-hidden">
                        <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-title-sm text-title-sm">Tren Sales & Profit Bulanan</h3>
                            <div class="flex gap-4">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-600"></span><span
                                        class="text-xs font-semibold text-on-surface-variant">Sales</span></div>
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-green-500"></span><span
                                        class="text-xs font-semibold text-on-surface-variant">Profit</span></div>
                            </div>
                        </div>
                        <div class="p-4 h-72">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    {{-- Distribusi Region (donut) --}}
                    <div class="col-span-12 lg:col-span-4 bg-white border border-outline-variant rounded-xl p-4">
                        <h3 class="font-title-sm text-title-sm mb-4">Sales per Region</h3>
                        <div class="h-52 flex items-center justify-center">
                            <canvas id="regionChart"></canvas>
                        </div>
                        <div class="mt-4 space-y-2">
                            @foreach($region as $i => $r)
                                @php $colors = ['bg-blue-600', 'bg-green-500', 'bg-amber-400', 'bg-red-400']; @endphp
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $colors[$i] ?? 'bg-slate-400' }}"></span>
                                        <span class="text-on-surface-variant">{{ $r['region'] }}</span>
                                    </div>
                                    <span class="font-semibold">${{ number_format($r['total_sales'], 0) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Charts Row 2 ──────────────────────────────────── --}}
                <div class="grid grid-cols-12 gap-6 mb-6">

                    {{-- Sales per Tahun --}}
                    <div class="col-span-12 md:col-span-4 bg-white border border-outline-variant rounded-xl overflow-hidden">
                        <div class="p-4 border-b border-slate-100">
                            <h3 class="font-title-sm text-title-sm">Sales per Tahun</h3>
                        </div>
                        <div class="p-4 h-56">
                            <canvas id="yearlyChart"></canvas>
                        </div>
                    </div>

                    {{-- Profit per Kategori --}}
                    <div class="col-span-12 md:col-span-4 bg-white border border-outline-variant rounded-xl overflow-hidden">
                        <div class="p-4 border-b border-slate-100">
                            <h3 class="font-title-sm text-title-sm">Profit per Kategori</h3>
                        </div>
                        <div class="p-4 h-56">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    {{-- Sales per Segmen --}}
                    <div class="col-span-12 md:col-span-4 bg-white border border-outline-variant rounded-xl overflow-hidden">
                        <div class="p-4 border-b border-slate-100">
                            <h3 class="font-title-sm text-title-sm">Sales per Segmen</h3>
                        </div>
                        <div class="p-4 h-56">
                            <canvas id="segmentChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- ── Top 10 Produk ─────────────────────────────────── --}}
                <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-title-sm text-title-sm">Top 10 Produk Terlaris</h3>
                        <span
                            class="text-xs font-bold bg-blue-50 text-blue-700 px-2 py-1 rounded-full border border-blue-100">Berdasarkan
                            Total Sales</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-surface-container-low">
                                <tr>
                                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">#</th>
                                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">PRODUK</th>
                                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">KATEGORI</th>
                                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant text-right">
                                        TOTAL SALES</th>
                                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant text-right">
                                        TOTAL PROFIT</th>
                                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant text-right">QTY
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($products as $i => $p)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-bold text-on-surface-variant">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-semibold text-on-surface leading-snug">{{ $p['product_name'] }}
                                            </p>
                                            <p class="text-xs text-on-surface-variant">{{ $p['sub_category'] }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                                @if($p['category'] === 'Technology') bg-blue-50 text-blue-700
                                                @elseif($p['category'] === 'Furniture') bg-amber-50 text-amber-700
                                                @else bg-green-50 text-green-700 @endif">
                                                {{ $p['category'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-sm font-semibold">
                                            ${{ number_format($p['total_sales'], 0) }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-sm text-green-600 font-semibold">
                                            ${{ number_format($p['total_profit'], 0) }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-sm">{{ $p['total_qty'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif {{-- end apiError check --}}
        </div>
    </div>
@endsection

@push('scripts')
    @if(!isset($apiError))
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const monthly = @json($monthly);
            const yearly = @json($yearly);
            const category = @json($category);
            const region = @json($region);
            const segment = @json($segment);

            // ── Tren Bulanan (Line) ───────────────────────────────────────
            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: monthly.map(d => d.period),
                    datasets: [
                        {
                            label: 'Sales',
                            data: monthly.map(d => d.total_sales),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37,99,235,0.07)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                        },
                        {
                            label: 'Profit',
                            data: monthly.map(d => d.total_profit),
                            borderColor: '#22c55e',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: false,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9 }, color: '#94a3b8',
                                maxTicksLimit: 12,
                                callback: (val, i) => monthly[i]?.month === 1 ? monthly[i]?.period : ''
                            }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 10 }, color: '#94a3b8',
                                callback: v => '$' + (v / 1000).toFixed(0) + 'K'
                            }
                        }
                    }
                }
            });

            // ── Sales per Tahun (Bar) ─────────────────────────────────────
            new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: {
                    labels: yearly.map(d => d.year),
                    datasets: [{
                        label: 'Total Sales',
                        data: yearly.map(d => d.total_sales),
                        backgroundColor: ['#bfdbfe', '#93c5fd', '#60a5fa', '#2563eb'],
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#64748b' } },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10 }, color: '#94a3b8', callback: v => '$' + (v / 1000).toFixed(0) + 'K' }
                        }
                    }
                }
            });

            // ── Profit per Kategori (Bar grouped) ────────────────────────
            new Chart(document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: category.map(d => d.category),
                    datasets: [
                        {
                            label: 'Sales',
                            data: category.map(d => d.total_sales),
                            backgroundColor: '#93c5fd',
                            borderRadius: 4,
                        },
                        {
                            label: 'Profit',
                            data: category.map(d => d.total_profit),
                            backgroundColor: '#86efac',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 10 }, boxWidth: 10, padding: 10 }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10 }, callback: v => '$' + (v / 1000).toFixed(0) + 'K' }
                        }
                    }
                }
            });

            // ── Region (Doughnut) ─────────────────────────────────────────
            new Chart(document.getElementById('regionChart'), {
                type: 'doughnut',
                data: {
                    labels: region.map(d => d.region),
                    datasets: [{
                        data: region.map(d => d.total_sales),
                        backgroundColor: ['#2563eb', '#22c55e', '#f59e0b', '#f87171'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    cutout: '65%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // ── Segmen (Bar Horizontal) ───────────────────────────────────
            new Chart(document.getElementById('segmentChart'), {
                type: 'bar',
                data: {
                    labels: segment.map(d => d.segment),
                    datasets: [{
                        label: 'Total Sales',
                        data: segment.map(d => d.total_sales),
                        backgroundColor: ['#2563eb', '#0891b2', '#7c3aed'],
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10 }, callback: v => '$' + (v / 1000).toFixed(0) + 'K' }
                        },
                        y: { grid: { display: false }, ticks: { font: { size: 11 } } }
                    }
                }
            });
        </script>
    @endif
@endpush