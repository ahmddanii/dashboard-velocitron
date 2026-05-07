@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <div class="max-w-[1440px] mx-auto">

            {{-- ── Error: Flask tidak jalan ──────────────────────── --}}
            @if(isset($apiError))
                @include('dashboard.partials.api-error')
            @else
                @include('dashboard.partials.dashboard-header')
                {{-- KPI CARDS --}}
                @include('dashboard.partials.kpi-cards')

                @includeIf("dashboard.roles.$role")

            @endif {{-- end apiError --}}
        </div>
    </div>
@endsection

@push('scripts')
    @if(!isset($apiError))
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const COLORS = {
                blue: '#2563eb', green: '#22c55e',
                amber: '#f59e0b', red: '#f87171',
                purple: '#a855f7', cyan: '#06b6d4',
                blueLt: '#93c5fd', greenLt: '#86efac',
            };

            // ── Monthly (head-analytics only) ────────────────────────────
            @if($role === 'head-analytics' && !empty($monthly))
                new Chart(document.getElementById('monthlyChart'), {
                    type: 'line',
                    data: {
                        labels: @json(array_column($monthly, 'period')),
                        datasets: [
                            {
                                label: 'Sales',
                                data: @json(array_column($monthly, 'total_sales')),
                                borderColor: COLORS.blue, backgroundColor: 'rgba(37,99,235,0.07)',
                                borderWidth: 2.5, tension: 0.4, fill: true, pointRadius: 0, pointHoverRadius: 4,
                            },
                            {
                                label: 'Profit',
                                data: @json(array_column($monthly, 'total_profit')),
                                borderColor: COLORS.green, borderWidth: 2, tension: 0.4,
                                fill: false, pointRadius: 0, pointHoverRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 9 }, color: '#94a3b8', maxTicksLimit: 12 } },
                            y: {
                                grid: { color: '#f1f5f9' }, ticks: {
                                    font: { size: 10 }, color: '#94a3b8',
                                    callback: v => '$' + (v / 1000).toFixed(0) + 'K'
                                }
                            }
                        }
                    }
                });
            @endif

            // ── Yearly ────────────────────────────────────────────────────
            @if(!empty($yearly))
                new Chart(document.getElementById('yearlyChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column($yearly, 'year')),
                        datasets: [{
                            label: 'Total Sales',
                            data: @json(array_column($yearly, 'total_sales')),
                            backgroundColor: [COLORS.blueLt, '#60a5fa', '#3b82f6', COLORS.blue],
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#64748b' } },
                            y: {
                                grid: { color: '#f1f5f9' }, ticks: {
                                    font: { size: 10 }, color: '#94a3b8',
                                    callback: v => '$' + (v / 1000).toFixed(0) + 'K'
                                }
                            }
                        }
                    }
                });
            @endif

            // ── Category ──────────────────────────────────────────────────
            @if(!empty($category))
                new Chart(document.getElementById('categoryChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column(array_values((array) $category), 'category')),
                        datasets: [
                            {
                                label: 'Sales', data: @json(array_column(array_values((array) $category), 'total_sales')),
                                backgroundColor: COLORS.blueLt, borderRadius: 4
                            },
                            {
                                label: 'Profit', data: @json(array_column(array_values((array) $category), 'total_profit')),
                                backgroundColor: COLORS.greenLt, borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 10, padding: 10 } } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                            y: {
                                grid: { color: '#f1f5f9' }, ticks: {
                                    font: { size: 10 },
                                    callback: v => '$' + (v / 1000).toFixed(0) + 'K'
                                }
                            }
                        }
                    }
                });
            @endif

            // ── Region — doughnut (head-analytics & KAM) ─────────────────
            @if(!empty($region) && in_array($role, ['head-analytics', 'key-account-manager']))
                new Chart(document.getElementById('regionChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_column($region, 'region')),
                        datasets: [{
                            data: @json(array_column($region, 'total_sales')),
                            backgroundColor: [COLORS.blue, COLORS.green, COLORS.amber, COLORS.red],
                            borderWidth: 0, hoverOffset: 6
                        }]
                    },
                    options: {
                        cutout: '65%', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            @endif

            // ── Region — grouped bar (financial-controller) ───────────────
            @if(!empty($region) && $role === 'financial-controller')
                new Chart(document.getElementById('regionChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column($region, 'region')),
                        datasets: [
                            {
                                label: 'Sales', data: @json(array_column($region, 'total_sales')),
                                backgroundColor: COLORS.blueLt, borderRadius: 4
                            },
                            {
                                label: 'Profit', data: @json(array_column($region, 'total_profit')),
                                backgroundColor: COLORS.greenLt, borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 10 } } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '$' + (v / 1000).toFixed(0) + 'K' } }
                        }
                    }
                });
            @endif

            // ── Region — horizontal bar (logistics-officer) ───────────────
            @if(!empty($region) && $role === 'logistics-officer')
                new Chart(document.getElementById('regionBarChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column($region, 'region')),
                        datasets: [{
                            label: 'Total Orders',
                            data: @json(array_column($region, 'total_orders')),
                            backgroundColor: [COLORS.blue, COLORS.green, COLORS.amber, COLORS.red],
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } }
                    }
                });
            @endif

            // ── Segment (head-analytics & KAM) ───────────────────────────
            @if(!empty($segment))
                new Chart(document.getElementById('segmentChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column(array_values((array) $segment), 'segment')),
                        datasets: [{
                            label: 'Total Sales',
                            data: @json(array_column(array_values((array) $segment), 'total_sales')),
                            backgroundColor: [COLORS.blue, COLORS.cyan, COLORS.purple],
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '$' + (v / 1000).toFixed(0) + 'K' } },
                            y: { grid: { display: false } }
                        }
                    }
                });
            @endif
        </script>
    @endif
@endpush