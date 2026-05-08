@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

    <div class="p-6">

        <div class="max-w-[1440px] mx-auto">

            <div class="flex justify-between items-center mb-8">

                <div>

                    <h2 class="font-display-lg text-display-lg">
                        Transaction History
                    </h2>

                    <p class="text-on-surface-variant mt-1">

                        Riwayat keputusan DSS & approval transaksi.

                    </p>

                </div>

            </div>



            <div class="flex justify-end mb-6">

                <a href="{{ route('transactions.export') }}" class="inline-flex items-center gap-2
                    px-4 py-2 rounded-xl
                    bg-green-600 text-white
                    text-sm font-semibold
                    hover:bg-green-700 transition">

                    <span class="material-symbols-outlined">

                        download

                    </span>

                    Export CSV

                </a>

            </div>
        </div>

        <x-ui.card class="overflow-hidden">


            <div class="dashboard-card-header">

                <h3 class="dashboard-title">
                    Approval Audit Trail
                </h3>



                <div class="dashboard-card-body overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="border-b border-outline-variant">

                                <th class="text-left py-3">
                                    Transaction
                                </th>

                                <th class="text-left py-3">
                                    Requester
                                </th>

                                <th class="text-left py-3">
                                    Prediction
                                </th>

                                <th class="text-left py-3">
                                    Confidence
                                </th>

                                <th class="text-left py-3">
                                    Decision
                                </th>

                                <th class="text-left py-3">
                                    Approved By
                                </th>

                                <th class="text-left py-3">
                                    Date
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($transactions as $trx)

                                                <tr class="border-b border-outline-variant">

                                                    {{-- Transaction --}}
                                                    <td class="py-4">

                                                        <div>

                                                            <p class="font-semibold">
                                                                {{ $trx->title }}
                                                            </p>

                                                            <p class="text-xs text-on-surface-variant mt-1">

                                                                {{ $trx->request_type }}

                                                            </p>

                                                        </div>

                                                    </td>

                                                    {{-- Requester --}}
                                                    <td class="py-4">

                                                        {{ $trx->requester->name }}

                                                    </td>

                                                    {{-- Prediction --}}
                                                    <td class="py-4">

                                                        @if($trx->prediction)

                                                                                <span class="inline-flex items-center gap-1.5
                                                                                                                                                                                                                                                                                            px-2 py-1 rounded-full text-xs font-bold

                                                                                                                                                                                                                                                                                            {{ $trx->prediction == 'Menguntungkan'
                                                            ? 'bg-green-100 text-green-700'
                                                            : 'bg-red-100 text-red-700' }}">

                                                                                    <span class="material-symbols-outlined text-sm">

                                                                                        {{ $trx->prediction == 'Menguntungkan'
                                                            ? 'trending_up'
                                                            : 'warning' }}

                                                                                    </span>

                                                                                    {{ $trx->prediction }}

                                                                                </span>

                                                        @else

                                                            <span class="text-slate-400">
                                                                -
                                                            </span>

                                                        @endif

                                                    </td>

                                                    {{-- Confidence --}}
                                                    <td class="py-4">

                                                        {{ $trx->confidence ?? '-' }}

                                                    </td>

                                                    {{-- Status --}}
                                                    <td class="py-4">

                                                        <span class="inline-flex items-center gap-1.5
                                                                                                                                                                px-2 py-1 rounded-full text-xs font-bold

                                                                                                                                                                {{ $trx->status == 'approved'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">

                                                            <span class="material-symbols-outlined text-sm">

                                                                {{ $trx->status == 'approved'
                                ? 'check_circle'
                                : 'cancel' }}

                                                            </span>

                                                            {{ ucfirst($trx->status) }}

                                                        </span>

                                                    </td>

                                                    {{-- Approver --}}
                                                    <td class="py-4">

                                                        {{ $trx->approver->name ?? '-' }}

                                                    </td>

                                                    {{-- Date --}}
                                                    <td class="py-4 text-on-surface-variant">

                                                        {{ optional($trx->approved_at)->format('d M Y H:i') }}

                                                    </td>

                                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="py-10 text-center text-slate-400">

                                        Belum ada histori transaksi.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

        </x-ui.card>

    </div>

    </div>

@endsection