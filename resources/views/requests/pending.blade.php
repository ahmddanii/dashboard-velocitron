@extends('layouts.app')

@section('title', 'Pending Requests')

@section('content')

    <div class="p-6" x-data="{ 
        showReviewModal: false, 
        loading: false, 
        requestData: null, 
        dssResult: null,
        review(id) {
            this.loading = true;
            this.showReviewModal = true;
            this.requestData = null;
            this.dssResult = null;
            
            fetch(`/requests/${id}/api-review`)
                .then(res => res.json())
                .then(data => {
                    this.requestData = data.request;
                    this.dssResult = data.result;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
        }
    }">

        <div class="max-w-[1440px] mx-auto">

            <div class="flex justify-between items-center mb-8">

                <div>

                    <h2 class="font-display-lg text-display-lg">
                        Pending Requests
                    </h2>

                    <p class="text-on-surface-variant mt-1">

                        Review transaksi sebelum diproses DSS.

                    </p>

                </div>

            </div>

            <x-ui.card class="overflow-hidden">

                <div class="dashboard-card-header">

                    <h3 class="dashboard-title">
                        Request Queue
                    </h3>

                </div>

                <div class="dashboard-card-body overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="border-b border-outline-variant">

                                <th class="text-left py-3">
                                    Request
                                </th>

                                <th class="text-left py-3">
                                    Requester
                                </th>

                                <th class="text-left py-3">
                                    Type
                                </th>

                                <th class="text-left py-3">
                                    Sales
                                </th>

                                <th class="text-left py-3">
                                    Status
                                </th>

                                <th class="text-right py-3">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($requests as $request)

                                <tr class="border-b border-outline-variant">

                                    <td class="py-4">

                                        <div>

                                            <p class="font-semibold">
                                                {{ $request->title }}
                                            </p>

                                            <p class="text-xs text-on-surface-variant mt-1">

                                                {{ $request->description }}

                                            </p>

                                        </div>

                                    </td>

                                    <td class="py-4">

                                        {{ $request->requester->name }}

                                    </td>

                                    <td class="py-4 capitalize">

                                        {{ $request->request_type }}

                                    </td>

                                    <td class="py-4">

                                        ${{ number_format($request->sales, 0) }}

                                    </td>

                                    <td class="py-4">

                                        <span class="px-2 py-1 rounded-full text-xs font-bold
                                                        bg-amber-100 text-amber-700">

                                            Pending

                                        </span>

                                    </td>

                                    <td class="py-4 text-right">

                                        <button @click="review({{ $request->id }})" class="inline-flex items-center gap-2
                                                        px-3 py-2 rounded-lg
                                                        bg-secondary text-white
                                                        text-xs font-semibold hover:bg-secondary/90 transition-all active:scale-95">

                                            <span class="material-symbols-outlined text-sm">
                                                psychology
                                            </span>

                                            Review DSS

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="py-10 text-center text-slate-400">

                                        Tidak ada pending request.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </x-ui.card>

        </div>

        {{-- MODAL REVIEW --}}
        <div x-show="showReviewModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div @click.away="showReviewModal = false" 
                 class="bg-surface rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                {{-- Header --}}
                <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                    <div>
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">psychology</span>
                            DSS Decision Review
                        </h3>
                        <p class="text-sm text-on-surface-variant mt-1" x-text="loading ? 'Analyzing data...' : 'Analysis complete'"></p>
                    </div>
                    <button @click="showReviewModal = false" class="w-10 h-10 rounded-full hover:bg-outline-variant/20 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto p-8 bg-surface">
                    
                    {{-- Loading State --}}
                    <div x-show="loading" class="flex flex-col items-center justify-center py-20">
                        <div class="w-12 h-12 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div>
                        <p class="mt-4 font-bold text-secondary animate-pulse uppercase tracking-widest text-xs">DSS is analyzing transaction...</p>
                    </div>

                    {{-- Data Content --}}
                    <div x-show="!loading && requestData" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- Left: Transaction Detail --}}
                        <div class="space-y-6">
                            <h4 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant border-b pb-2">Transaction Details</h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Requester</p>
                                    <p class="font-bold mt-1" x-text="requestData?.requester?.name"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Requested At</p>
                                    <p class="font-bold mt-1" x-text="new Date(requestData?.created_at).toLocaleDateString()"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Title</p>
                                    <p class="font-bold mt-1" x-text="requestData?.title"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Type</p>
                                    <p class="font-bold mt-1 capitalize" x-text="requestData?.request_type"></p>
                                </div>
                            </div>

                            <div>
                                <p class="text-[10px] text-on-surface-variant uppercase font-bold">Description</p>
                                <p class="mt-1 text-sm text-on-surface-variant" x-text="requestData?.description"></p>
                            </div>

                            <div class="grid grid-cols-3 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Sales</p>
                                    <p class="font-black text-lg text-primary" x-text="'$' + Number(requestData?.sales).toLocaleString()"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Discount</p>
                                    <p class="font-black text-lg text-primary" x-text="(requestData?.discount * 100) + '%'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Qty</p>
                                    <p class="font-black text-lg text-primary" x-text="requestData?.quantity"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Region</p>
                                    <p class="font-semibold mt-1" x-text="requestData?.region"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold">Ship Mode</p>
                                    <p class="font-semibold mt-1" x-text="requestData?.ship_mode"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Right: DSS Decision --}}
                        <div class="space-y-6">
                            <h4 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant border-b pb-2">DSS Analysis Result</h4>
                            
                            <template x-if="dssResult">
                                <div class="space-y-6">
                                    <div class="rounded-3xl p-6 border transition-all duration-500" 
                                         :class="dssResult.prediction == 1 ? 'border-green-200 bg-green-50/50' : 'border-red-200 bg-red-50/50'">
                                        
                                        <div class="flex items-center gap-4 mb-6">
                                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg"
                                                 :class="dssResult.prediction == 1 ? 'bg-green-600 text-white shadow-green-200' : 'bg-red-600 text-white shadow-red-200'">
                                                <span class="material-symbols-outlined text-3xl" x-text="dssResult.prediction == 1 ? 'verified' : 'report'"></span>
                                            </div>
                                            <div>
                                                <p class="text-[10px] uppercase tracking-widest font-black" :class="dssResult.prediction == 1 ? 'text-green-600' : 'text-red-600'">Intelligence Prediction</p>
                                                <p class="text-3xl font-black tracking-tighter" :class="dssResult.prediction == 1 ? 'text-green-800' : 'text-red-800'" x-text="dssResult.label_id"></p>
                                            </div>
                                        </div>

                                        <div class="space-y-5">
                                            {{-- Profit Prob --}}
                                            <div>
                                                <div class="flex justify-between text-xs font-bold mb-2">
                                                    <span class="text-green-700 uppercase tracking-wider">Profit Probability</span>
                                                    <span class="text-green-700 text-sm" x-text="dssResult.prob_profitable + '%'"></span>
                                                </div>
                                                <div class="h-3 bg-white rounded-full overflow-hidden border border-green-100">
                                                    <div class="h-full bg-green-500 rounded-full transition-all duration-1000" :style="'width: ' + dssResult.prob_profitable + '%'"></div>
                                                </div>
                                            </div>

                                            {{-- Loss Prob --}}
                                            <div>
                                                <div class="flex justify-between text-xs font-bold mb-2">
                                                    <span class="text-red-600 uppercase tracking-wider">Loss Probability</span>
                                                    <span class="text-red-600 text-sm" x-text="dssResult.prob_loss + '%'"></span>
                                                </div>
                                                <div class="h-3 bg-white rounded-full overflow-hidden border border-red-100">
                                                    <div class="h-full bg-red-500 rounded-full transition-all duration-1000" :style="'width: ' + dssResult.prob_loss + '%'"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 flex gap-3">
                                        <span class="material-symbols-outlined text-blue-500">info</span>
                                        <p class="text-xs text-blue-700 leading-relaxed font-medium">
                                            DSS merekomendasikan untuk <strong x-text="dssResult.prediction == 1 ? 'SETUJU' : 'TOLAK'"></strong> transaksi ini berdasarkan data historis profitabilitas.
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!dssResult">
                                <div class="rounded-2xl p-6 bg-red-50 border border-red-200 text-center">
                                    <span class="material-symbols-outlined text-red-500 text-4xl mb-2">error</span>
                                    <p class="text-red-700 font-bold">API Offline</p>
                                    <p class="text-red-600 text-xs mt-1">Gagal menghubungi engine DSS untuk analisis otomatis.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Footer Action --}}
                <div class="px-8 py-6 bg-surface-container-low border-t border-outline-variant">
                    <div class="flex gap-4" x-show="!loading && requestData">
                        <button @click="showReviewModal = false" class="px-6 py-3 rounded-2xl border border-outline-variant font-bold text-sm hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <div class="flex-1 flex gap-4 justify-end">
                            <form method="POST" :action="'/requests/' + requestData?.id + '/reject'" class="flex-1 max-w-[200px]">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-2xl bg-red-500 text-white font-bold text-sm hover:bg-red-600 transition-all shadow-lg shadow-red-200 active:scale-95">
                                    Reject
                                </button>
                            </form>
                            <form method="POST" :action="'/requests/' + requestData?.id + '/approve'" class="flex-1 max-w-[200px]">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-2xl bg-green-600 text-white font-bold text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-200 active:scale-95">
                                    Approve Transaction
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection