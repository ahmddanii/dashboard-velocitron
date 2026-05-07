@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <div class="max-w-[1440px] mx-auto">

            {{-- Page Header --}}
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="font-display-lg text-display-lg text-on-surface">System Overview</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Real-time health monitoring and resource utilization for Warehouse Cluster Alpha-01.</p>
                </div>
                <div class="flex gap-2">
                    <button class="flex items-center gap-2 px-3 py-1.5 border border-outline-variant rounded-lg font-body-sm text-body-sm text-on-surface-variant hover:bg-white transition-all">
                        <span class="material-symbols-outlined text-sm">calendar_today</span> Last 24 Hours
                        <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>
                    <button class="flex items-center gap-2 px-3 py-1.5 border border-outline-variant rounded-lg font-body-sm text-body-sm text-on-surface-variant hover:bg-white transition-all">
                        <span class="material-symbols-outlined text-sm">download</span> Export Report
                    </button>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">storage</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Data Managed</p>
                        <p class="font-headline-md text-headline-md">{{ $kpi['total_data'] }}</p>
                        <p class="font-body-sm text-body-sm text-green-600 flex items-center">
                            <span class="material-symbols-outlined text-xs mr-1">trending_up</span>
                            +{{ $kpi['data_growth'] }}% <span class="text-on-surface-variant ml-1">vs last month</span>
                        </p>
                    </div>
                </div>
                <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">hub</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Active Pipelines</p>
                        <p class="font-headline-md text-headline-md">{{ $kpi['active_pipelines'] }} <span class="text-body-sm font-normal text-on-surface-variant">/ {{ $kpi['total_pipelines'] }}</span></p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> All systems operational
                        </p>
                    </div>
                </div>
                <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">bolt</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">System Uptime</p>
                        <p class="font-headline-md text-headline-md">{{ $kpi['uptime'] }}%</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Status: <span class="text-blue-600 font-semibold">Stable</span></p>
                    </div>
                </div>
            </div>

            {{-- Bento Layout --}}
            <div class="grid grid-cols-12 gap-6">

                {{-- Left Column --}}
                <div class="col-span-12 lg:col-span-8 space-y-6">

                    {{-- IOPS Line Chart (Chart.js) --}}
                    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
                        <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-title-sm text-title-sm">System Performance (IOPS)</h3>
                            <div class="flex gap-4">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-600"></span><span class="text-xs font-semibold text-on-surface-variant">Read</span></div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-slate-300"></span><span class="text-xs font-semibold text-on-surface-variant">Write</span></div>
                            </div>
                        </div>
                        <div class="p-4 h-64">
                            <canvas id="iopsChart"></canvas>
                        </div>
                    </div>

                    {{-- Pipeline Activity Table --}}
                    <div class="bg-white border border-outline-variant rounded-xl">
                        <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-title-sm text-title-sm">Recent Pipelines Activity</h3>
                            <a href="{{ route('pipelines') }}" class="text-blue-600 font-label-caps text-label-caps hover:underline">View Full Log</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-surface-container-low">
                                    <tr>
                                        <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">PIPELINE NAME</th>
                                        <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">STATUS</th>
                                        <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">DURATION</th>
                                        <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">ROWS PROCESSED</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($recentPipelines as $pipeline)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-4 font-body-sm text-body-sm font-semibold">{{ $pipeline['name'] }}</td>
                                            <td class="px-4 py-4">
                                                @if($pipeline['status'] === 'Successful')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">Successful</span>
                                                @elseif($pipeline['status'] === 'Running')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">Running</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700">Failed</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 font-data-tabular text-data-tabular">{{ $pipeline['duration'] }}</td>
                                            <td class="px-4 py-4 font-data-tabular text-data-tabular">{{ $pipeline['rows'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Right Sidebar --}}
                <div class="col-span-12 lg:col-span-4 space-y-6">

                    {{-- Storage Donut --}}
                    <div class="bg-white border border-outline-variant rounded-xl p-4">
                        <h3 class="font-title-sm text-title-sm mb-4">Storage Utilization</h3>
                        <div class="relative flex justify-center mb-4">
                            <canvas id="storageDonut" width="192" height="192" style="max-width:192px;max-height:192px;"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-headline-md text-headline-md">{{ $storage['percent'] }}%</span>
                                <span class="text-xs text-slate-400 font-medium">Used</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-600"></span><span class="text-on-surface-variant font-medium">Primary Store</span></div>
                                <span class="font-bold">{{ $storage['primary'] }} TB</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-slate-400"></span><span class="text-on-surface-variant font-medium">Archival Cold Storage</span></div>
                                <span class="font-bold">{{ $storage['archival'] }} TB</span>
                            </div>
                            <div class="flex justify-between items-center text-sm pt-2 border-t border-slate-100">
                                <span class="text-on-surface-variant">Remaining Capacity</span>
                                <span class="text-blue-600 font-bold">{{ $storage['remaining'] }} TB</span>
                            </div>
                        </div>
                        <button class="w-full mt-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-lg text-sm font-semibold text-slate-700 transition-all border border-slate-200">Manage Storage</button>
                    </div>

                    {{-- System Alerts --}}
                    <div class="bg-white border border-outline-variant rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-title-sm text-title-sm">System Alerts</h3>
                            <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-[10px] font-bold">{{ count($alerts) }}</span>
                        </div>
                        <div class="space-y-4">
                            @foreach($alerts as $alert)
                                <div class="flex gap-3 items-start">
                                    <div class="mt-1 p-1 {{ $alert['type'] === 'warning' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }} rounded">
                                        <span class="material-symbols-outlined text-sm">{{ $alert['type'] === 'warning' ? 'warning' : 'info' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold leading-tight">{{ $alert['title'] }}</p>
                                        <p class="text-xs text-on-surface-variant mt-1">{{ $alert['message'] }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $alert['time'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pipeline Health + Cluster Resources --}}
                    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden flex flex-col">
                        <div class="flex-1 p-4 border-b border-outline-variant">
                            <p class="text-[11px] font-bold text-outline uppercase tracking-wider mb-2">Pipeline Health</p>
                            <div class="flex items-center gap-4">
                                <div class="relative w-20 h-20">
                                    <svg class="w-full h-full" viewBox="0 0 36 36">
                                        <path class="stroke-current text-surface-container" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="3"/>
                                        <path class="stroke-current text-secondary" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-dasharray="{{ $pipelineHealth['rate'] }}, 100" stroke-linecap="round" stroke-width="3"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center font-bold text-lg">{{ $pipelineHealth['rate'] }}%</div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-body-sm"><span class="w-2 h-2 rounded-full bg-secondary"></span><span>Successful: {{ number_format($pipelineHealth['successful']) }}</span></div>
                                    <div class="flex items-center gap-2 text-body-sm"><span class="w-2 h-2 rounded-full bg-error"></span><span>Errors: {{ number_format($pipelineHealth['errors']) }}</span></div>
                                    <div class="flex items-center gap-2 text-body-sm"><span class="w-2 h-2 rounded-full bg-surface-container-highest"></span><span>Ignored: {{ number_format($pipelineHealth['ignored']) }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 p-4 flex flex-col justify-center">
                            <p class="text-[11px] font-bold text-outline uppercase tracking-wider mb-2">Cluster Resources</p>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-[11px] mb-1 font-semibold uppercase"><span>Memory (RAM)</span><span>{{ $cluster['ram_used'] }} / {{ $cluster['ram_total'] }} TB</span></div>
                                    <div class="h-1.5 bg-surface-container rounded-full overflow-hidden">
                                        <div class="h-full bg-secondary" style="width: {{ $cluster['ram_percent'] }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-[11px] mb-1 font-semibold uppercase"><span>Storage</span><span>{{ $cluster['storage_used'] }} / {{ $cluster['storage_total'] }} PB</span></div>
                                    <div class="h-1.5 bg-surface-container rounded-full overflow-hidden">
                                        <div class="h-full bg-secondary" style="width: {{ $cluster['storage_percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAB --}}
        <button class="fixed bottom-8 right-8 w-14 h-14 bg-secondary text-white rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center justify-center z-50">
            <span class="material-symbols-outlined text-2xl">add</span>
        </button>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // ── IOPS Line Chart ──────────────────────────────────────────
    const iopsCtx = document.getElementById('iopsChart').getContext('2d');
    new Chart(iopsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($iopsChart['labels']) !!},
            datasets: [
                {
                    label: 'Read',
                    data: {!! json_encode($iopsChart['read']) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.07)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                },
                {
                    label: 'Write',
                    data: {!! json_encode($iopsChart['write']) !!},
                    borderColor: '#cbd5e1',
                    borderDash: [4, 4],
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
            plugins: { legend: { display: false }, tooltip: { mode: 'index' } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8' } },
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, color: '#94a3b8' } }
            }
        }
    });

    // ── Storage Donut ────────────────────────────────────────────
    const donutCtx = document.getElementById('storageDonut').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $storage['primary'] }}, {{ $storage['archival'] }}, {{ $storage['remaining'] }}],
                backgroundColor: ['#2563eb', '#94a3b8', '#f1f5f9'],
                borderWidth: 0,
                hoverOffset: 4,
            }]
        },
        options: {
            cutout: '72%',
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            responsive: true,
            maintainAspectRatio: true,
        }
    });
    </script>
@endpush