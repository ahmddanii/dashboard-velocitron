@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
    <div class="p-6" x-data="{ tab: 'dss' }">
        <div class="max-w-[1440px] mx-auto">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-display-lg text-display-lg">Transaction History</h2>
                    <p class="text-on-surface-variant mt-1">Riwayat keputusan DSS & data historis Superstore.</p>
                </div>
                @role('financial-controller')
                    <a href="{{ route('transactions.export') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">
                        <span class="material-symbols-outlined text-base">download</span>
                        Export CSV
                    </a>
                @endrole
            </div>

            {{-- Tab Switcher --}}
            <div class="flex gap-1 mb-6 border-b border-outline-variant">
                <button @click="tab = 'dss'"
                    :class="tab === 'dss'
                        ? 'border-b-2 border-secondary text-secondary bg-blue-50/50'
                        : 'text-on-surface-variant hover:text-on-surface'"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold transition rounded-t-lg">
                    <span class="material-symbols-outlined text-base">rule</span>
                    DSS Requests
                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-surface-container">
                        {{ $transactions->total() }}
                    </span>
                </button>
                <button @click="tab = 'historical'"
                    :class="tab === 'historical'
                        ? 'border-b-2 border-secondary text-secondary bg-blue-50/50'
                        : 'text-on-surface-variant hover:text-on-surface'"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold transition rounded-t-lg">
                    <span class="material-symbols-outlined text-base">dataset</span>
                    Historical Orders
                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-surface-container">
                        {{ number_format($historicalTotal) }}
                    </span>
                </button>
            </div>

            {{-- ── Tab: DSS Requests ── --}}
            <div x-show="tab === 'dss'" x-cloak>
                <x-ui.card class="overflow-hidden">
                    <div class="dashboard-card-header">
                        <h3 class="dashboard-title">Approval Audit Trail</h3>
                        <p class="dashboard-subtitle">Keputusan DSS yang telah diproses Financial Controller.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-surface-container-low">
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Transaction</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Requester</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Prediction</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Confidence</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Decision</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Approved By</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($transactions as $trx)
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-on-surface">{{ $trx->title }}</p>
                                            <p class="text-xs text-on-surface-variant mt-0.5 capitalize">{{ $trx->request_type }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-sm">{{ $trx->requester->name }}</td>
                                        <td class="px-5 py-4">
                                            @if($trx->prediction)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-bold
                                                    {{ $trx->prediction == 'Menguntungkan' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    <span class="material-symbols-outlined text-sm">
                                                        {{ $trx->prediction == 'Menguntungkan' ? 'trending_up' : 'warning' }}
                                                    </span>
                                                    {{ $trx->prediction }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            @if($trx->confidence)
                                                <span class="font-mono font-semibold">{{ $trx->confidence }}%</span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <x-ui.status-badge :status="$trx->status" />
                                        </td>
                                        <td class="px-5 py-4 text-sm">{{ $trx->approver->name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm text-on-surface-variant">
                                            {{ optional($trx->approved_at)->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">rule</span>
                                            Belum ada histori transaksi DSS.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages())
                        <div class="p-4 border-t border-outline-variant">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </x-ui.card>
            </div>

            {{-- ── Tab: Historical Orders ── --}}
            <div x-show="tab === 'historical'" x-cloak>

                {{-- Filter bar --}}
                <form method="GET" class="flex gap-3 mb-4 flex-wrap">
                    <input type="hidden" name="tab" value="historical">
                    <select name="category" onchange="this.form.submit()"
                        class="px-3 py-2 rounded-xl border border-outline-variant bg-white text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30">
                        <option value="">All Categories</option>
                        <option value="Technology"      {{ request('category') === 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Furniture"       {{ request('category') === 'Furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="Office Supplies" {{ request('category') === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                    </select>
                    <select name="region" onchange="this.form.submit()"
                        class="px-3 py-2 rounded-xl border border-outline-variant bg-white text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30">
                        <option value="">All Regions</option>
                        <option value="East"    {{ request('region') === 'East' ? 'selected' : '' }}>East</option>
                        <option value="West"    {{ request('region') === 'West' ? 'selected' : '' }}>West</option>
                        <option value="Central" {{ request('region') === 'Central' ? 'selected' : '' }}>Central</option>
                        <option value="South"   {{ request('region') === 'South' ? 'selected' : '' }}>South</option>
                    </select>
                    <select name="segment" onchange="this.form.submit()"
                        class="px-3 py-2 rounded-xl border border-outline-variant bg-white text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30">
                        <option value="">All Segments</option>
                        <option value="Consumer"    {{ request('segment') === 'Consumer' ? 'selected' : '' }}>Consumer</option>
                        <option value="Corporate"   {{ request('segment') === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                        <option value="Home Office" {{ request('segment') === 'Home Office' ? 'selected' : '' }}>Home Office</option>
                    </select>
                    @if(request('category') || request('region') || request('segment'))
                        <a href="{{ route('transactions.history') }}"
                            class="px-3 py-2 rounded-xl border border-outline-variant text-sm text-on-surface-variant hover:bg-surface-container transition">
                            Reset
                        </a>
                    @endif
                </form>

                <x-ui.card class="overflow-hidden">
                    <div class="dashboard-card-header">
                        <h3 class="dashboard-title">Superstore Historical Orders</h3>
                        <p class="dashboard-subtitle">Data historis dari dataset Superstore DW.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-surface-container-low">
                                    <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Order ID</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Customer</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Product</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Category</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Region</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Ship Mode</th>
                                    <th class="text-right px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Sales</th>
                                    <th class="text-right px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Profit</th>
                                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($historicalOrders as $order)
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="px-4 py-3 text-xs font-mono text-on-surface-variant">{{ $order['order_id'] }}</td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium text-on-surface">{{ $order['customer_name'] }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $order['segment'] }}</p>
                                        </td>
                                        <td class="px-4 py-3 max-w-[180px]">
                                            <p class="text-sm truncate text-on-surface">{{ $order['product_name'] }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $order['sub_category'] }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                                @if($order['category'] === 'Technology') bg-blue-50 text-blue-700
                                                @elseif($order['category'] === 'Furniture') bg-amber-50 text-amber-700
                                                @else bg-green-50 text-green-700 @endif">
                                                {{ $order['category'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">{{ $order['region'] }}</td>
                                        <td class="px-4 py-3 text-sm text-on-surface-variant">{{ $order['ship_mode'] }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-sm font-semibold">
                                            ${{ number_format($order['sales'], 0) }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-sm font-semibold
                                            {{ $order['profit'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                            ${{ number_format($order['profit'], 0) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($order['is_profitable'])
                                                <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-50 text-green-700">Profitable</span>
                                            @else
                                                <span class="text-xs font-bold px-2 py-1 rounded-full bg-red-50 text-red-600">Loss</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-12 text-center text-on-surface-variant">
                                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">dataset</span>
                                            Tidak ada data historis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Manual pagination untuk data dari Flask --}}
                    @if($historicalLastPage > 1)
                        <div class="p-4 border-t border-outline-variant flex items-center justify-between">
                            <p class="text-xs text-on-surface-variant">
                                Halaman {{ $historicalPage }} dari {{ $historicalLastPage }}
                            </p>
                            <div class="flex gap-2">
                                @if($historicalPage > 1)
                                    <a href="{{ request()->fullUrlWithQuery(['historical_page' => $historicalPage - 1, 'tab' => 'historical']) }}"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-outline-variant hover:bg-surface-container transition">
                                        ← Prev
                                    </a>
                                @endif
                                @if($historicalPage < $historicalLastPage)
                                    <a href="{{ request()->fullUrlWithQuery(['historical_page' => $historicalPage + 1, 'tab' => 'historical']) }}"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-outline-variant hover:bg-surface-container transition">
                                        Next →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </x-ui.card>
            </div>

        </div>
    </div>

    {{-- Auto-switch ke tab historical kalau dari filter --}}
    @if(request('tab') === 'historical')
        <script>
            document.addEventListener('alpine:init', () => {
                // handled by x-data default, override via URL param
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.__initialTab = 'historical';
            });
        </script>
    @endif

@endsection