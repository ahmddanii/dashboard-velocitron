@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
    <div class="p-6" x-data="{ 
        tab: '{{ request('tab', 'dss') }}',
        editData: {
            id: '',
            title: '',
            description: '',
            sales: 0,
            quantity: 1,
            discount: 0,
            shipping_days: 4,
            category: '',
            segment: '',
            region: '',
            ship_mode: '',
            updateUrl: ''
        },
        cancelUrl: '',
        showExportPreview: false,
        exportPreviewData: [],
        exportTotalCount: 0,
        isLoadingPreview: false,
        fetchExportPreview() {
            this.isLoadingPreview = true;
            this.showExportPreview = true;
            fetch('{{ route('export.preview') }}')
                .then(res => res.json())
                .then(data => {
                    this.exportPreviewData = data.data;
                    this.exportTotalCount = data.count;
                    this.isLoadingPreview = false;
                });
        },
        showDetailModal: false,
        detailType: '',
        detailData: {},
        showImportModal: {{ $errors->has('csv_file') ? 'true' : 'false' }},
        showClearConfirm: false,
        importedCount: {{ $importedTotal }},
        showImportedDetail: false,
        isUploading: false,
        isDropping: false,
        selectedFileName: '',
        isSyncing: false,
        syncProgress: 0,
        syncTotal: 0,
        syncRemaining: {{ $syncRemaining ?? 0 }},
        importedDetailData: {},
        importedDetailLoading: false,
        syncAIStatus() {
            if (this.isSyncing) return;
            this.isSyncing = true;
            this.syncTotal = this.syncRemaining;
            
            const processNext = () => {
                fetch('{{ route('admin.ajax-predict-imported') }}')
                    .then(r => r.json())
                    .then(data => {
                        if (data.finished) {
                            this.isSyncing = false;
                            this.syncRemaining = 0;
                            this.syncProgress = 100;
                            window.location.reload(); // Reload biar datanya update
                            return;
                        }
                        this.syncRemaining = data.remaining;
                        this.syncProgress = Math.round(((this.syncTotal - data.remaining) / this.syncTotal) * 100);
                        setTimeout(processNext, 500); // Jeda dikit biar gak kenceng bgt
                    })
                    .catch(err => {
                        console.error('Sync Error:', err);
                        this.isSyncing = false;
                    });
            };
            processNext();
        },
        runImportedDSS() {
            if (!this.importedDetailData.review_url) return;
            this.importedDetailLoading = true;
            fetch(this.importedDetailData.review_url)
                .then(r => r.json())
                .then(data => {
                    if (data.result) {
                        const r = data.result;
                        const profitable = r.label_id === 'Profitable' || r.prediction === 1;
                        this.importedDetailData.prediction = profitable ? 'Profitable' : 'Loss';
                        this.importedDetailData.confidence = r.prob_profitable ? Math.round(r.prob_profitable * 100) : null;
                    }
                    this.importedDetailLoading = false;
                })
                .catch(() => { this.importedDetailLoading = false; });
        }
    }">
        <div class="max-w-[1440px] mx-auto">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-display-lg text-display-lg">Transaction History</h2>
                    <p class="text-on-surface-variant mt-1">Riwayat keputusan DSS & data historis Superstore.</p>
                </div>
                @if(auth()->user()->hasAnyRole(['financial-controller', 'head-analytics']))
                    <div class="flex gap-3">
                        <button @click="showImportModal = true"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-container text-on-surface text-sm font-semibold hover:bg-surface-container-high transition shadow-sm border border-outline-variant">
                            <span class="material-symbols-outlined text-base">upload</span>
                            Import Data
                        </button>
                        <button @click="fetchExportPreview()"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition shadow-sm hover:shadow-md">
                            <span class="material-symbols-outlined text-base">download</span>
                            Export Data
                        </button>
                    </div>
                @endif
            </div>

            {{-- Tab Switcher --}}
            <div class="flex gap-1 mb-6 border-b border-outline-variant">
                <a href="{{ route('transactions.history', ['tab' => 'dss']) }}"
                    :class="tab === 'dss'
                        ? 'border-b-2 border-secondary text-secondary bg-blue-50/50'
                        : 'text-on-surface-variant hover:text-on-surface'"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-semibold transition rounded-t-lg">
                    <span class="material-symbols-outlined text-base">rule</span>
                    DSS Requests
                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-surface-container">
                        {{ number_format($dssTotal) }}
                    </span>
                </a>
                <a href="{{ route('transactions.history', ['tab' => 'imported']) }}"
                    :class="tab === 'imported'
                        ? 'border-b-2 border-secondary text-secondary bg-blue-50/50'
                        : 'text-on-surface-variant hover:text-on-surface'"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-semibold transition rounded-t-lg">
                    <span class="material-symbols-outlined text-base">upload_file</span>
                    Imported Data
                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-surface-container">
                        {{ number_format($importedTotal) }}
                    </span>
                </a>
                <a href="{{ route('transactions.history', ['tab' => 'historical']) }}"
                    :class="tab === 'historical'
                        ? 'border-b-2 border-secondary text-secondary bg-blue-50/50'
                        : 'text-on-surface-variant hover:text-on-surface'"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-semibold transition rounded-t-lg">
                    <span class="material-symbols-outlined text-base">dataset</span>
                    Historical Orders
                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-surface-container">
                        {{ number_format($historicalTotal) }}
                    </span>
                </a>
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
                                    @unless(auth()->user()->hasAnyRole(['financial-controller', 'head-analytics']))
                                    <th class="text-center px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Action</th>
                                    @endunless
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($transactions as $trx)
                                    @if(request('tab', 'dss') === 'dss')
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-on-surface">{{ $trx->title }}</p>
                                            <p class="text-xs text-on-surface-variant mt-0.5 capitalize">{{ $trx->request_type }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-sm">{{ $trx->requester->name }}</td>
                                        <td class="px-5 py-4">
                                            @if($trx->prediction)
                                                @php
                                                    $isProfitable = in_array($trx->prediction, ['Profitable', '1', 'Menguntungkan']);
                                                @endphp
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                                    {{ $isProfitable ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                                    <span class="material-symbols-outlined text-sm">
                                                        {{ $isProfitable ? 'trending_up' : 'warning' }}
                                                    </span>
                                                    {{ $isProfitable ? 'Profitable' : 'Loss' }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-xs">Waiting Prediction</span>
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
                                            {{ optional($trx->created_at)->format('d M Y H:i') }}
                                        </td>
                                        @unless(auth()->user()->hasAnyRole(['financial-controller', 'head-analytics']))
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" 
                                                    @click="
                                                        detailType = 'dss';
                                                        detailData = {{ json_encode($trx->toArray()) }};
                                                        detailData.requester_name = '{{ $trx->requester->name }}';
                                                        detailData.approver_name = '{{ $trx->approver->name ?? '-' }}';
                                                        detailData.formatted_date = '{{ optional($trx->created_at)->format('d M Y, H:i') }}';
                                                        showDetailModal = true;
                                                    "
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container text-on-surface text-xs font-bold hover:bg-surface-container-high transition">
                                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                                    Detail
                                                </button>
                                                
                                                @if($trx->status === 'pending' && $trx->requester_id === auth()->id())
                                                    <button type="button" 
                                                        @click="
                                                            editData = {
                                                                id: '{{ $trx->id }}',
                                                                title: '{{ addslashes($trx->title) }}',
                                                                description: '{{ addslashes($trx->description) }}',
                                                                sales: {{ $trx->sales }},
                                                                quantity: {{ $trx->quantity }},
                                                                discount: {{ $trx->discount }},
                                                                shipping_days: {{ $trx->shipping_days }},
                                                                category: '{{ $trx->category }}',
                                                                segment: '{{ $trx->segment }}',
                                                                region: '{{ $trx->region }}',
                                                                ship_mode: '{{ $trx->ship_mode }}',
                                                                updateUrl: '{{ route('requests.update', $trx->id) }}'
                                                            };
                                                            $dispatch('open-modal', 'edit-request-modal')
                                                        "
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-bold hover:bg-blue-100 transition">
                                                        <span class="material-symbols-outlined text-sm">edit</span>
                                                        Edit
                                                    </button>
                                                    <button type="button" 
                                                        @click="$dispatch('set-cancel-url', '{{ route('requests.cancel', $trx->id) }}'); $dispatch('open-modal', 'confirm-request-cancellation')"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100 transition">
                                                        <span class="material-symbols-outlined text-sm">close</span>
                                                        Cancel
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        @endunless
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->hasAnyRole(['financial-controller', 'head-analytics']) ? 7 : 8 }}" class="py-12 text-center text-on-surface-variant">
                                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">rule</span>
                                            Belum ada histori transaksi DSS.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages() && request('tab', 'dss') === 'dss')
                        <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant">
                            {{ $transactions->links('vendor.pagination.premium') }}
                        </div>
                    @endif
                </x-ui.card>
            </div>

            {{-- ── Tab: Imported Data ── --}}
            <div x-show="tab === 'imported'" x-cloak>
                <x-ui.card class="overflow-hidden">
                    <div class="dashboard-card-header flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex-1">
                            <h3 class="dashboard-title">Historical Dataset</h3>
                            <div class="flex flex-wrap items-center gap-4 mt-2">
                                <p class="dashboard-subtitle m-0">
                                    {{ $importedTotal }} data historis yang diunggah via Bulk Import CSV.
                                </p>
                                
                                {{-- Sync Progress --}}
                                <template x-if="syncRemaining > 0 || isSyncing">
                                    <div class="flex items-center gap-3 bg-blue-50 dark:bg-blue-500/10 px-3 py-1.5 rounded-xl border border-blue-100 dark:border-blue-500/20">
                                        <div class="text-right">
                                            <p class="text-[9px] font-black uppercase text-blue-600 dark:text-blue-400 leading-none mb-0.5">AI Prediction Sync</p>
                                            <p class="text-[10px] font-bold text-on-surface" x-text="isSyncing ? 'Processing ' + syncProgress + '%' : syncRemaining + ' records left'"></p>
                                        </div>
                                        <div class="w-20 h-1.5 bg-surface-container rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-600 transition-all duration-500" :style="'width: ' + (isSyncing ? syncProgress : 0) + '%'"></div>
                                        </div>
                                        <button @click="syncAIStatus()" x-show="!isSyncing"
                                            class="px-2.5 py-1 rounded-lg bg-blue-600 text-white text-[9px] font-black uppercase hover:bg-blue-700 transition shadow-sm">
                                            Sync Now
                                        </button>
                                        <div x-show="isSyncing" class="w-3 h-3 border-2 border-blue-600/30 border-t-blue-600 rounded-full animate-spin"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Clear imported data button --}}
                        @if($importedTotal > 0)
                        <button type="button" @click="showClearConfirm = true" :disabled="isSyncing"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-sm font-bold hover:bg-red-100 dark:hover:bg-red-500/20 transition border border-red-200 dark:border-red-500/20 shrink-0 disabled:opacity-50">
                            <span class="material-symbols-outlined text-sm">delete_sweep</span>
                            Clear All ({{ $importedTotal }})
                        </button>
                        @endif
                    </div>

                    {{-- Info banner --}}
                    <div class="px-6 py-3 bg-blue-50/50 dark:bg-blue-500/5 border-b border-blue-100 dark:border-blue-500/10 flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-blue-500 text-sm">info</span>
                        <p class="text-xs text-blue-700 dark:text-blue-300">
                            Data historis ini bersifat <strong>read-only</strong>. DSS Prediction dijalankan secara otomatis untuk analisis. Tidak memerlukan proses approval.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-surface-container-low">
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Transaction</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Category</th>
                                    <th class="text-right px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Sales</th>
                                    <th class="text-center px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Qty</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Segment / Region</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Profit Status</th>
                                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Import Date</th>
                                    <th class="text-center px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($transactions as $trx)
                                    @if(request('tab') === 'imported')
                                    @php 
                                        $isProfitable = in_array($trx->prediction, ['Profitable', '1', 'Menguntungkan', 'Untung']); 
                                        $isLoss = in_array($trx->prediction, ['Loss', '0', 'Rugi']);
                                    @endphp
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="px-5 py-3.5">
                                            <p class="font-semibold text-on-surface text-sm leading-tight">{{ Str::limit($trx->title, 40) }}</p>
                                            <p class="text-[10px] text-on-surface-variant mt-0.5 capitalize">{{ $trx->request_type }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="inline-block px-2 py-0.5 rounded-lg bg-surface-container text-on-surface-variant text-xs font-semibold">
                                                {{ $trx->category ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <span class="font-mono font-bold text-on-surface">${{ number_format($trx->sales, 2) }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-on-surface-variant">{{ $trx->quantity }}</td>
                                        <td class="px-5 py-3.5">
                                            <p class="text-xs font-semibold text-on-surface">{{ $trx->segment ?? '-' }}</p>
                                            <p class="text-[10px] text-on-surface-variant">{{ $trx->region ?? '-' }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            @if($trx->prediction)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                                    {{ $isProfitable ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                                    <span class="material-symbols-outlined text-sm">{{ $isProfitable ? 'trending_up' : 'trending_down' }}</span>
                                                    {{ $isProfitable ? 'Untung' : ($isLoss ? 'Rugi' : $trx->prediction) }}
                                                    @if($trx->confidence)
                                                        <span class="opacity-70 font-mono">({{ round($trx->confidence, 0) }}%)</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 italic">
                                                    <span class="material-symbols-outlined text-sm">question_mark</span>
                                                    No Status
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-on-surface-variant">
                                            {{ optional($trx->created_at)->format('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <button type="button"
                                                @click="
                                                    importedDetailData = {
                                                        id: {{ $trx->id }},
                                                        title: @js($trx->title),
                                                        request_type: @js($trx->request_type),
                                                        category: @js($trx->category ?? '-'),
                                                        sales: {{ $trx->sales ?? 0 }},
                                                        quantity: {{ $trx->quantity ?? 0 }},
                                                        discount: {{ $trx->discount ?? 0 }},
                                                        shipping_days: {{ $trx->shipping_days ?? 0 }},
                                                        segment: @js($trx->segment ?? '-'),
                                                        region: @js($trx->region ?? '-'),
                                                        ship_mode: @js($trx->ship_mode ?? '-'),
                                                        description: @js($trx->description ?? ''),
                                                        prediction: @js($trx->prediction),
                                                        confidence: {{ $trx->confidence ?? 'null' }},
                                                        date: @js(optional($trx->created_at)->format('d M Y')),
                                                        uploader: @js($trx->requester?->name ?? '-'),
                                                        review_url: @js(route('requests.api-review', $trx->id))
                                                    };
                                                    showImportedDetail = true;
                                                "
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container text-on-surface text-xs font-bold hover:bg-surface-container-high transition">
                                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-16 text-center text-on-surface-variant">
                                            <span class="material-symbols-outlined text-5xl block mb-3 opacity-20">upload_file</span>
                                            <p class="font-semibold text-sm">Belum ada data yang diimport.</p>
                                            <p class="text-xs mt-1 opacity-60">Gunakan tombol "Bulk Import" di atas untuk mengunggah dataset CSV.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages() && request('tab') === 'imported')
                        <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant">
                            {{ $transactions->links('vendor.pagination.premium') }}
                        </div>
                    @endif
                </x-ui.card>
            </div>


            {{-- ── Tab: Historical Orders ── --}}
            <div x-show="tab === 'historical'" x-cloak>

                {{-- Filter bar --}}
                <form method="GET" class="flex gap-3 mb-4 flex-wrap">
                    <input type="hidden" name="tab" value="historical">
                    {{-- Category Filter --}}
                    <x-ui.filter-select name="category" :selected="request('category')" :options="[
                        '' => 'All Categories',
                        'Technology' => 'Technology',
                        'Furniture' => 'Furniture',
                        'Office Supplies' => 'Office Supplies',
                    ]" />

                    {{-- Region Filter --}}
                    <x-ui.filter-select name="region" :selected="request('region')" :options="[
                        '' => 'All Regions',
                        'East' => 'East',
                        'West' => 'West',
                        'Central' => 'Central',
                        'South' => 'South',
                    ]" />

                    {{-- Segment Filter --}}
                    <x-ui.filter-select name="segment" :selected="request('segment')" :options="[
                        '' => 'All Segments',
                        'Consumer' => 'Consumer',
                        'Corporate' => 'Corporate',
                        'Home Office' => 'Home Office',
                    ]" />
                    @if(request('category') || request('region') || request('segment'))
                        <a href="{{ route('transactions.history', ['tab' => 'historical']) }}"
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
                                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($historicalOrders as $order)
                                    <tr class="hover:bg-surface-container-low transition-colors text-[11px]">
                                        <td class="px-4 py-3 font-mono text-on-surface-variant">{{ $order['order_id'] }}</td>
                                        <td class="px-4 py-3">
                                            <p class="font-bold text-on-surface">{{ $order['customer_name'] }}</p>
                                            <p class="text-[10px] text-on-surface-variant">{{ $order['segment'] }}</p>
                                        </td>
                                        <td class="px-4 py-3 max-w-[150px]">
                                            <p class="truncate font-bold text-on-surface">{{ $order['product_name'] }}</p>
                                            <p class="text-[10px] text-on-surface-variant">{{ $order['sub_category'] }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold px-2 py-0.5 rounded-full
                                                @if($order['category'] === 'Technology') bg-blue-50 text-blue-700
                                                @elseif($order['category'] === 'Furniture') bg-amber-50 text-amber-700
                                                @else bg-green-50 text-green-700 @endif">
                                                {{ $order['category'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $order['region'] }}</td>
                                        <td class="px-4 py-3 text-on-surface-variant">{{ $order['ship_mode'] }}</td>
                                        <td class="px-4 py-3 text-right font-mono font-black">
                                            ${{ number_format($order['sales'], 0) }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-black
                                            {{ $order['profit'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                            ${{ number_format($order['profit'], 0) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($order['is_profitable'])
                                                <span class="text-[10px] font-black px-2 py-1 rounded-full bg-green-50 text-green-700 uppercase tracking-widest">Profitable</span>
                                            @else
                                                <span class="text-[10px] font-black px-2 py-1 rounded-full bg-red-50 text-red-600 uppercase tracking-widest">Loss</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" 
                                                @click="
                                                    detailType = 'historical';
                                                    detailData = {{ json_encode($order) }};
                                                    showDetailModal = true;
                                                "
                                                class="p-1.5 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high transition shadow-sm active:scale-95">
                                                <span class="material-symbols-outlined text-base">visibility</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="py-12 text-center text-on-surface-variant">
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
                        <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="px-3 py-1 rounded-full bg-secondary/10 text-secondary text-[10px] font-black uppercase tracking-widest">
                                    Page {{ $historicalPage }} of {{ $historicalLastPage }}
                                </div>
                                <p class="text-xs text-on-surface-variant font-medium">
                                    Showing {{ count($historicalOrders) }} of {{ number_format($historicalTotal) }} records
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                @if($historicalPage > 1)
                                    <a href="{{ request()->fullUrlWithQuery(['historical_page' => $historicalPage - 1, 'tab' => 'historical']) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface hover:bg-surface-container transition-all active:scale-95 shadow-sm">
                                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                                        Previous
                                    </a>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl border border-outline-variant bg-slate-50 text-slate-300 cursor-not-allowed">
                                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                                        Previous
                                    </div>
                                @endif

                                <div class="h-8 w-[1px] bg-outline-variant mx-1"></div>

                                @if($historicalPage < $historicalLastPage)
                                    <a href="{{ request()->fullUrlWithQuery(['historical_page' => $historicalPage + 1, 'tab' => 'historical']) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-secondary text-white hover:bg-secondary/90 transition-all active:scale-95 shadow-lg shadow-secondary/20">
                                        Next
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </a>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-slate-200 text-slate-400 cursor-not-allowed">
                                        Next
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </x-ui.card>
            </div>

            {{-- Edit Request Modal --}}
            <x-modal name="edit-request-modal" focusable maxWidth="2xl">
                <div class="p-6">
                    <form method="post" :action="editData.updateUrl">
                        @csrf
                        @method('put')

                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20 shadow-sm">
                                <span class="material-symbols-outlined text-2xl">edit_note</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-on-surface">Edit {{ $requestMeta['title'] }}</h2>
                                <p class="text-xs text-on-surface-variant">Perbarui parameter transaksi untuk perhitungan DSS ulang.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            {{-- Title & Description --}}
                            <div class="grid grid-cols-1 gap-4 p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Title</label>
                                    <input type="text" name="title" x-model="editData.title" required
                                        class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-secondary/20 transition-all text-on-surface">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1.5">Description</label>
                                    <textarea name="description" x-model="editData.description" rows="2"
                                        class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-secondary/20 transition-all text-on-surface"></textarea>
                                </div>
                            </div>

                            {{-- Financials --}}
                            <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                                <div>
                                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1.5">
                                        {{ $requestMeta['fields']['sales']['label'] }}
                                    </label>
                                    <input type="number" name="sales" step="0.01" x-model="editData.sales" required
                                        class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['quantity']['label'] }}
                                    </label>
                                    <input type="number" name="quantity" x-model="editData.quantity" required
                                        class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                </div>
                                
                                @if($requestMeta['fields']['discount']['show'])
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['discount']['label'] }}
                                    </label>
                                    <select name="discount" x-model="editData.discount" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                        @foreach([0.0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8] as $d)
                                            <option value="{{ $d }}">{{ $d * 100 }}%</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="discount" :value="editData.discount">
                                @endif
                            </div>

                            {{-- Logistics & Classification --}}
                            <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                                @if($requestMeta['fields']['shipping_days']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['shipping_days']['label'] }}
                                    </label>
                                    <input type="number" name="shipping_days" x-model="editData.shipping_days" required
                                        class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                </div>
                                @else
                                    <input type="hidden" name="shipping_days" :value="editData.shipping_days">
                                @endif

                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['category']['label'] }}
                                    </label>
                                    <select name="category" x-model="editData.category" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                        @foreach($requestMeta['fields']['category']['options'] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($requestMeta['fields']['segment']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['segment']['label'] }}
                                    </label>
                                    <select name="segment" x-model="editData.segment" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                        @foreach($requestMeta['fields']['segment']['options'] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="segment" :value="editData.segment">
                                @endif

                                @if($requestMeta['fields']['region']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['region']['label'] }}
                                    </label>
                                    <select name="region" x-model="editData.region" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                        @foreach($requestMeta['fields']['region']['options'] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                @if($requestMeta['fields']['ship_mode']['show'])
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $requestMeta['fields']['ship_mode']['label'] }}
                                    </label>
                                    <select name="ship_mode" x-model="editData.ship_mode" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface">
                                        @foreach($requestMeta['fields']['ship_mode']['options'] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="ship_mode" :value="editData.ship_mode">
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <button type="button" x-on:click="$dispatch('close')"
                                class="px-5 py-2.5 rounded-lg border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition-all">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-8 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                                Simpan Perubahan
                            </button>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>

            {{-- Cancel Confirmation Modal --}}
            <x-modal name="confirm-request-cancellation" focusable maxWidth="md">
                <div class="p-8 text-center" x-data="{ url: '' }" @set-cancel-url.window="url = $event.detail">
                    <form method="post" :action="url">
                        @csrf
                        @method('delete')

                        <div class="flex flex-col items-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-red-600 mb-4">
                                <span class="material-symbols-outlined text-4xl">warning</span>
                            </div>
                            <h2 class="text-xl font-bold text-on-surface">
                                Batalkan Request?
                            </h2>
                        </div>

                        <p class="text-sm text-on-surface-variant mb-8 leading-relaxed">
                            Apakah Anda yakin ingin membatalkan request ini? Tindakan ini akan menghapus request dari antrian secara permanen.
                        </p>

                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button type="button" x-on:click="$dispatch('close')"
                                class="order-2 sm:order-1 px-5 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition-all">
                                Tetap Simpan
                            </button>

                            <button type="submit"
                                class="order-1 sm:order-2 px-5 py-2.5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                                Ya, Batalkan Request
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
            {{-- Export Preview Modal --}}
            <div x-show="showExportPreview" 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-cloak>
                
                <div class="bg-surface rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden border border-outline-variant">
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                        <div>
                            <h3 class="text-xl font-bold text-on-surface">Pratinjau Data Export</h3>
                            <p class="text-xs text-on-surface-variant">Menampilkan 10 data terbaru dari total <span x-text="exportTotalCount" class="font-bold text-primary"></span> baris.</p>
                        </div>
                        <button @click="showExportPreview = false" class="p-2 hover:bg-surface-container rounded-full transition">
                            <span class="material-symbols-outlined text-on-surface-variant">close</span>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 max-h-[60vh] overflow-y-auto">
                        <template x-if="isLoadingPreview">
                            <div class="flex flex-col items-center justify-center py-20 gap-4">
                                <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-on-surface-variant animate-pulse font-medium">Menyiapkan data...</p>
                            </div>
                        </template>

                        <template x-if="!isLoadingPreview">
                            <div class="overflow-x-auto rounded-xl border border-outline-variant">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead class="bg-surface-container text-on-surface-variant sticky top-0 uppercase tracking-tighter font-bold">
                                        <tr>
                                            <th class="px-4 py-3 border-b border-outline-variant">Title</th>
                                            <th class="px-4 py-3 border-b border-outline-variant">Type</th>
                                            <th class="px-4 py-3 border-b border-outline-variant text-right">Sales</th>
                                            <th class="px-4 py-3 border-b border-outline-variant text-center">Status</th>
                                            <th class="px-4 py-3 border-b border-outline-variant">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant">
                                        <template x-for="item in exportPreviewData" :key="item.id">
                                            <tr class="hover:bg-surface-container-low transition-colors">
                                                <td class="px-4 py-3 font-medium text-on-surface" x-text="item.title"></td>
                                                <td class="px-4 py-3 text-on-surface-variant uppercase text-[10px]" x-text="item.request_type"></td>
                                                <td class="px-4 py-3 text-right font-mono" x-text="'$' + parseFloat(item.sales).toLocaleString()"></td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                                        :class="{
                                                            'bg-green-100 text-green-700': item.status === 'approved',
                                                            'bg-red-100 text-red-700': item.status === 'rejected',
                                                            'bg-amber-100 text-amber-700': item.status === 'pending'
                                                        }"
                                                        x-text="item.status.toUpperCase()">
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-on-surface-variant italic" x-text="new Date(item.created_at).toLocaleDateString()"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant flex justify-between items-center">
                        <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                            <span class="material-symbols-outlined text-green-600">info</span>
                            Seluruh data (<span x-text="exportTotalCount"></span> baris) akan di-export ke format .CSV
                        </div>
                        <div class="flex gap-3">
                            <button @click="showExportPreview = false" 
                                class="px-5 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition-all">
                                Batal
                            </button>
                            <a href="{{ route('transactions.export') }}" 
                                @click="showExportPreview = false"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-green-600 text-white text-sm font-bold hover:bg-green-700 transition shadow-lg shadow-green-200">
                                <span class="material-symbols-outlined text-base">download</span>
                                Download CSV Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Global Detail Modal --}}
            <div x-show="showDetailModal" 
                class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @keydown.escape.window="showDetailModal = false"
                x-cloak>
                
                <div class="bg-surface rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden border border-outline-variant" @click.away="showDetailModal = false">
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                :class="detailType === 'dss' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400' : 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400'">
                                <span class="material-symbols-outlined" x-text="detailType === 'dss' ? 'rule' : 'dataset'"></span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-on-surface" x-text="detailType === 'dss' ? 'DSS Transaction Detail' : 'Historical Order Detail'"></h3>
                                <p class="text-xs text-on-surface-variant" x-text="detailType === 'dss' ? 'Rincian pengajuan keputusan DSS.' : 'Rincian data historis Superstore.'"></p>
                            </div>
                        </div>
                        <button @click="showDetailModal = false" class="p-2 hover:bg-surface-container rounded-full transition">
                            <span class="material-symbols-outlined text-on-surface-variant">close</span>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        {{-- DSS Detail --}}
                        <template x-if="detailType === 'dss'">
                            <div class="space-y-6">
                                {{-- Main Info --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Transaction Title</p>
                                        <p class="text-sm font-bold text-on-surface" x-text="detailData.title"></p>
                                        <p class="text-xs text-on-surface-variant mt-1 capitalize" x-text="detailData.request_type"></p>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Status & Date</p>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                                :class="{
                                                    'bg-green-100 text-green-700': detailData.status === 'approved',
                                                    'bg-red-100 text-red-700': detailData.status === 'rejected',
                                                    'bg-amber-100 text-amber-700': detailData.status === 'pending'
                                                }"
                                                x-text="detailData.status.toUpperCase()">
                                            </span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant" x-text="detailData.formatted_date"></p>
                                    </div>
                                </div>

                                {{-- DSS Result --}}
                                <div class="p-5 rounded-2xl bg-secondary/5 border border-secondary/20">
                                    <h4 class="text-xs font-bold text-secondary uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">analytics</span>
                                        DSS Prediction Result
                                    </h4>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] text-on-surface-variant mb-1">Prediction</p>
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-base" 
                                                    :class="detailData.prediction === 'Menguntungkan' ? 'text-green-600' : 'text-red-600'"
                                                    x-text="detailData.prediction === 'Menguntungkan' ? 'trending_up' : 'warning'"></span>
                                                <p class="text-sm font-bold" :class="detailData.prediction === 'Menguntungkan' ? 'text-green-600' : 'text-red-600'" x-text="detailData.prediction || '-'"></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-on-surface-variant mb-1">Confidence Score</p>
                                            <p class="text-lg font-mono font-black text-secondary" x-text="(detailData.confidence || 0) + '%'"></p>
                                        </div>
                                    </div>
                                    <div class="mt-4 h-1.5 w-full bg-secondary/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-secondary transition-all duration-1000" :style="'width: ' + (detailData.confidence || 0) + '%'"></div>
                                    </div>
                                </div>

                                {{-- Financials & Logistics --}}
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="p-3 rounded-xl border border-outline-variant">
                                        <p class="text-[10px] text-on-surface-variant mb-0.5">Sales</p>
                                        <p class="text-sm font-bold font-mono" x-text="'$' + parseFloat(detailData.sales || 0).toLocaleString()"></p>
                                    </div>
                                    <div class="p-3 rounded-xl border border-outline-variant">
                                        <p class="text-[10px] text-on-surface-variant mb-0.5">Quantity</p>
                                        <p class="text-sm font-bold" x-text="detailData.quantity"></p>
                                    </div>
                                    <div class="p-3 rounded-xl border border-outline-variant">
                                        <p class="text-[10px] text-on-surface-variant mb-0.5">Discount</p>
                                        <p class="text-sm font-bold" x-text="(detailData.discount * 100) + '%'"></p>
                                    </div>
                                    <div class="p-3 rounded-xl border border-outline-variant">
                                        <p class="text-[10px] text-on-surface-variant mb-0.5">Ship Mode</p>
                                        <p class="text-sm font-bold" x-text="detailData.ship_mode"></p>
                                    </div>
                                    <div class="p-3 rounded-xl border border-outline-variant">
                                        <p class="text-[10px] text-on-surface-variant mb-0.5">Shipping Days</p>
                                        <p class="text-sm font-bold" x-text="detailData.shipping_days + ' Days'"></p>
                                    </div>
                                    <div class="p-3 rounded-xl border border-outline-variant">
                                        <p class="text-[10px] text-on-surface-variant mb-0.5">Region</p>
                                        <p class="text-sm font-bold" x-text="detailData.region"></p>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div x-show="detailData.description">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2">Description</p>
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant text-sm text-on-surface italic">
                                        &ldquo;<span x-text="detailData.description"></span>&rdquo;
                                    </div>
                                </div>

                                {{-- Stakeholders --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2">Requester</p>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-xs font-bold" x-text="detailData.requester_name ? detailData.requester_name.charAt(0) : '?'"></div>
                                            <p class="text-sm font-medium" x-text="detailData.requester_name"></p>
                                        </div>
                                    </div>
                                    <div x-show="detailData.approver_name && detailData.approver_name !== '-'">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2">Approved By</p>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-bold" x-text="detailData.approver_name ? detailData.approver_name.charAt(0) : '?'"></div>
                                            <p class="text-sm font-medium" x-text="detailData.approver_name"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Decision Note --}}
                                <div x-show="detailData.decision_note">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2">Decision Note</p>
                                    <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/20 text-sm text-amber-900 dark:text-amber-300">
                                        <span x-text="detailData.decision_note"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Historical Detail --}}
                        <template x-if="detailType === 'historical'">
                            <div class="space-y-6">
                                {{-- Main Info --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Order ID</p>
                                        <p class="text-sm font-mono font-bold text-on-surface" x-text="detailData.order_id"></p>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Profitability</p>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                            :class="detailData.is_profitable ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                            x-text="detailData.is_profitable ? 'PROFITABLE' : 'LOSS'">
                                        </span>
                                    </div>
                                </div>

                                {{-- Product & Customer --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-5 rounded-2xl border border-outline-variant">
                                        <h4 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">person</span>
                                            Customer Info
                                        </h4>
                                        <p class="text-sm font-bold text-on-surface mb-1" x-text="detailData.customer_name"></p>
                                        <p class="text-xs text-on-surface-variant" x-text="detailData.segment"></p>
                                    </div>
                                    <div class="p-5 rounded-2xl border border-outline-variant">
                                        <h4 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">inventory_2</span>
                                            Product Info
                                        </h4>
                                        <p class="text-sm font-bold text-on-surface mb-1 truncate" :title="detailData.product_name" x-text="detailData.product_name"></p>
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            <span class="px-2 py-0.5 rounded-full bg-surface-container text-[10px] font-bold text-on-surface-variant" x-text="detailData.category"></span>
                                            <span class="px-2 py-0.5 rounded-full bg-surface-container text-[10px] font-bold text-on-surface-variant" x-text="detailData.sub_category"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Financial Performance --}}
                                <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant">
                                    <h4 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-4">Financial Performance</h4>
                                    <div class="grid grid-cols-2 gap-8">
                                        <div class="relative">
                                            <p class="text-[10px] text-on-surface-variant mb-1">Gross Sales</p>
                                            <p class="text-2xl font-mono font-black text-on-surface" x-text="'$' + parseFloat(detailData.sales || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="relative">
                                            <p class="text-[10px] text-on-surface-variant mb-1">Net Profit</p>
                                            <p class="text-2xl font-mono font-black" :class="detailData.profit >= 0 ? 'text-green-600' : 'text-red-500'" 
                                                x-text="'$' + parseFloat(detailData.profit || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                    {{-- Progress bar visual for profit margin if possible --}}
                                    <div class="mt-6 pt-4 border-t border-outline-variant/50">
                                        <div class="flex justify-between text-[10px] text-on-surface-variant mb-1.5 uppercase font-bold tracking-tighter">
                                            <span>Profitability Margin</span>
                                            <span x-text="detailData.profit >= 0 ? '+' : '' + ((detailData.profit / detailData.sales) * 100).toFixed(1) + '%'"></span>
                                        </div>
                                        <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-1000" 
                                                :class="detailData.profit >= 0 ? 'bg-green-500' : 'bg-red-500'"
                                                :style="'width: ' + Math.min(Math.abs((detailData.profit / detailData.sales) * 100), 100) + '%'"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Logistics --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl border border-outline-variant flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <span class="material-symbols-outlined">map</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-on-surface-variant uppercase font-bold">Region</p>
                                            <p class="text-sm font-bold" x-text="detailData.region"></p>
                                        </div>
                                    </div>
                                    <div class="p-4 rounded-xl border border-outline-variant flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                            <span class="material-symbols-outlined">local_shipping</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-on-surface-variant uppercase font-bold">Ship Mode</p>
                                            <p class="text-sm font-bold" x-text="detailData.ship_mode"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant flex justify-end">
                        <button @click="showDetailModal = false" 
                            class="px-8 py-2.5 rounded-xl bg-on-surface text-surface text-sm font-bold hover:opacity-90 transition shadow-lg">
                            Close Detail
                        </button>
                    </div>
                </div>
            </div>

            {{-- Clear Imported Data Confirmation Modal --}}
            <div x-show="showClearConfirm"
                class="fixed inset-0 z-[80] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-cloak>

                <div class="bg-surface rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border border-outline-variant"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @click.away="showClearConfirm = false">

                    {{-- Icon header --}}
                    <div class="px-6 pt-8 pb-4 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center mb-4 shadow-inner">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400" style="font-size:32px;font-variation-settings:'FILL' 1">delete_forever</span>
                        </div>
                        <h3 class="text-lg font-bold text-on-surface mb-2">Hapus Semua Data Import?</h3>
                        <p class="text-sm text-on-surface-variant leading-relaxed">
                            Tindakan ini akan menghapus <span class="font-bold text-red-600 dark:text-red-400" x-text="importedCount.toLocaleString()"></span> data historis yang diimport secara permanen dan <span class="font-semibold">tidak dapat dibatalkan</span>.
                        </p>
                    </div>

                    {{-- Warning banner --}}
                    <div class="mx-6 mb-6 p-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-red-500 text-sm shrink-0 mt-0.5">warning</span>
                        <p class="text-xs text-red-700 dark:text-red-300 leading-relaxed">
                            Data yang dihapus tidak akan bisa dikembalikan. Pastikan Anda sudah memiliki backup dataset sebelum melanjutkan.
                        </p>
                    </div>

                    {{-- Action buttons --}}
                    <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant flex gap-3">
                        <button type="button" @click="showClearConfirm = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition-all">
                            Batal
                        </button>
                        <form action="{{ route('transactions.import.clear') }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-500/30 active:scale-95">
                                Ya, Hapus Semua
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Imported Data Detail Modal ── --}}
            <div x-show="showImportedDetail"
                class="fixed inset-0 z-[75] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-cloak>

                <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-outline-variant"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    @click.away="showImportedDetail = false">

                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400" style="font-variation-settings:'FILL' 1">inventory</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface text-sm" x-text="importedDetailData.title"></p>
                                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider" x-text="importedDetailData.request_type"></p>
                            </div>
                        </div>
                        <button @click="showImportedDetail = false" class="p-2 hover:bg-surface-container rounded-full transition">
                            <span class="material-symbols-outlined text-on-surface-variant">close</span>
                        </button>
                    </div>

                    {{-- DSS Prediction Banner --}}
                    <div class="px-6 pt-5">
                        <div x-show="importedDetailData.prediction"
                            :class="(importedDetailData.prediction === 'Profitable' || importedDetailData.prediction === '1' || importedDetailData.prediction === 'Untung') ?
                                'bg-green-50 border-green-200 dark:bg-green-500/10 dark:border-green-500/20' :
                                'bg-red-50 border-red-200 dark:bg-red-500/10 dark:border-red-500/20'"
                            class="rounded-2xl border p-4 flex items-center gap-4 mb-4">
                            <div :class="(importedDetailData.prediction === 'Profitable' || importedDetailData.prediction === '1' || importedDetailData.prediction === 'Untung') ?
                                'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400' :
                                'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400'"
                                class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1"
                                    x-text="(importedDetailData.prediction === 'Profitable' || importedDetailData.prediction === '1' || importedDetailData.prediction === 'Untung') ? 'trending_up' : 'trending_down'"></span>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider opacity-60">Profit Status</p>
                                <p class="font-black text-lg"
                                    :class="(importedDetailData.prediction === 'Profitable' || importedDetailData.prediction === '1' || importedDetailData.prediction === 'Untung') ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400'"
                                    x-text="(importedDetailData.prediction === 'Profitable' || importedDetailData.prediction === '1' || importedDetailData.prediction === 'Untung') ? 'Untung' : 'Rugi'"></p>
                                <p class="text-xs text-on-surface-variant" x-show="importedDetailData.confidence">
                                    Confidence: <span class="font-mono font-bold" x-text="importedDetailData.confidence ? importedDetailData.confidence + '%' : '-'"></span>
                                </p>
                            </div>
                        </div>

                        {{-- No prediction yet --}}
                        <div x-show="!importedDetailData.prediction" class="rounded-2xl border border-outline-variant bg-surface-container-low p-4 flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-on-surface-variant">help_outline</span>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">Status tidak tersedia</p>
                                <p class="text-xs text-on-surface-variant">Data ini belum memiliki status profit/loss.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Data Grid --}}
                    <div class="px-6 pb-4 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Sales</p>
                            <p class="font-mono font-bold text-on-surface" x-text="'$' + Number(importedDetailData.sales).toLocaleString('en', {minimumFractionDigits:2})"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Quantity</p>
                            <p class="font-bold text-on-surface" x-text="importedDetailData.quantity"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Discount</p>
                            <p class="font-mono font-bold text-on-surface" x-text="(importedDetailData.discount * 100).toFixed(0) + '%'"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Shipping Days</p>
                            <p class="font-bold text-on-surface" x-text="importedDetailData.shipping_days + ' days'"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Category</p>
                            <p class="font-semibold text-on-surface" x-text="importedDetailData.category"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Ship Mode</p>
                            <p class="font-semibold text-on-surface" x-text="importedDetailData.ship_mode"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Segment</p>
                            <p class="font-semibold text-on-surface" x-text="importedDetailData.segment"></p>
                        </div>
                        <div class="rounded-xl bg-surface-container-low border border-outline-variant p-3">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mb-1">Region</p>
                            <p class="font-semibold text-on-surface" x-text="importedDetailData.region"></p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex gap-3">
                        <button type="button" @click="showImportedDetail = false"
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            {{-- Import Modal --}}
            <div x-show="showImportModal" 
                class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-cloak>
                
                <div class="bg-surface rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-outline-variant" @click.away="!isUploading && (showImportModal = false)">
                    <form action="{{ route('transactions.import') }}" method="POST" enctype="multipart/form-data" @submit="isUploading = true">
                        @csrf
                        <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <span class="material-symbols-outlined">upload_file</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-on-surface">Bulk Import Transactions</h3>
                                    <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-black">CSV Upload</p>
                                </div>
                            </div>
                            <button type="button" @click="showImportModal = false" class="p-2 hover:bg-surface-container rounded-full transition">
                                <span class="material-symbols-outlined text-on-surface-variant">close</span>
                            </button>
                        </div>

                        <div class="p-6">
                            <div class="mb-6 p-5 rounded-2xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100/50 dark:border-blue-500/20">
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                        <span class="material-symbols-outlined text-sm">info</span>
                                    </div>
                                    <div class="text-xs text-blue-800 dark:text-blue-200 leading-relaxed flex-1">
                                        <p class="font-bold mb-1">Panduan Kolom CSV:</p>
                                        Sistem akan otomatis mencocokkan nama kolom. Minimal harus ada kolom <b>Title</b> dan <b>Sales</b>.
                                    </div>
                                </div>
                                
                                <div class="flex gap-6 pl-11">
                                    <div class="flex-1">
                                        <span class="text-[9px] font-black uppercase text-blue-400 block mb-1">Wajib / Critical</span>
                                        <ul class="text-[10px] text-blue-700 dark:text-blue-300 space-y-1">
                                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-blue-400"></span> <b>Title</b> (Nama Produk)</li>
                                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-blue-400"></span> <b>Sales</b> (Harga/Nilai)</li>
                                        </ul>
                                    </div>
                                    <div class="flex-1 border-l border-blue-200/50 dark:border-blue-800/30 pl-6">
                                        <span class="text-[9px] font-black uppercase text-blue-400 block mb-1">Sangat Disarankan</span>
                                        <ul class="text-[10px] text-blue-700 dark:text-blue-300 space-y-1">
                                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-blue-300"></span> Category, Segment</li>
                                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-blue-300"></span> Discount, Ship Days</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div 
                                    @dragover.prevent="isDropping = true" 
                                    @dragleave.prevent="isDropping = false"
                                    @drop.prevent="
                                        isDropping = false;
                                        const files = $event.dataTransfer.files;
                                        if (files.length) {
                                            $refs.fileInput.files = files;
                                            selectedFileName = files[0].name;
                                        }
                                    "
                                    class="relative group">
                                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Select CSV File</label>
                                    
                                    <div :class="isDropping ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-950/20' : ($errors->has('csv_file') ? 'border-red-500 bg-red-50/50 dark:bg-red-950/20' : 'border-outline-variant hover:border-blue-400')"
                                        class="relative border-2 border-dashed rounded-2xl p-8 transition-all duration-200 text-center">
                                        
                                        <input type="file" name="csv_file" x-ref="fileInput" accept=".csv" required
                                            @change="selectedFileName = $el.files[0]?.name || ''"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="space-y-3">
                                            <div :class="$errors->has('csv_file') ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' : 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400'"
                                                class="w-12 h-12 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform duration-300">
                                                <span class="material-symbols-outlined text-3xl">
                                                    {{ $errors->has('csv_file') ? 'error' : 'upload_file' }}
                                                </span>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-sm font-bold text-on-surface" x-text="selectedFileName || 'Drag and drop your CSV here'"></p>
                                                @error('csv_file')
                                                    <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                                                @else
                                                    <p class="text-xs text-on-surface-variant" x-show="!selectedFileName">atau klik untuk telusuri file</p>
                                                    <p class="text-[10px] font-black text-blue-600 uppercase" x-show="selectedFileName">File siap di-import</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('transactions.template') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    Download CSV Template
                                </a>
                            </div>
                        </div>

                        <div class="px-6 py-5 bg-surface-container-low border-t border-outline-variant flex gap-3">
                            <button type="button" @click="showImportModal = false" :disabled="isUploading"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface hover:bg-surface-container transition disabled:opacity-50">
                                Batal
                            </button>
                            <button type="submit" :disabled="isUploading"
                                class="flex-2 inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 disabled:bg-blue-400 disabled:shadow-none">
                                <span x-show="!isUploading" class="material-symbols-outlined text-sm">cloud_upload</span>
                                <span x-show="isUploading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span x-text="isUploading ? 'Memproses Data...' : 'Mulai Import'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto-switch ke tab historical kalau dari filter --}}
    @if(request('tab') === 'historical')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.__initialTab = 'historical';
            });
        </script>
    @endif

@endsection