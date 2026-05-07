@extends('layouts.app')

@section('title', 'Review Request')

@section('content')

    <div class="p-6">

        <div class="max-w-5xl mx-auto">

            <div class="flex items-center gap-4 mb-8">

                <a href="{{ route('requests.pending') }}" class="p-2 rounded-lg border border-outline-variant">

                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>

                </a>

                <div>

                    <h2 class="font-display-lg text-display-lg">
                        DSS Review
                    </h2>

                    <p class="text-on-surface-variant mt-1">

                        Review transaksi sebelum approval.

                    </p>

                </div>

            </div>

            <div class="grid grid-cols-12 gap-6">

                {{-- LEFT --}}
                <x-ui.card class="col-span-12 lg:col-span-7 overflow-hidden">

                    <div class="dashboard-card-header">

                        <h3 class="dashboard-title">
                            Transaction Detail
                        </h3>

                    </div>

                    <div class="dashboard-card-body space-y-5">

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Request Title
                                </p>

                                <p class="font-semibold mt-1">
                                    {{ $requestData->title }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Request Type
                                </p>

                                <p class="font-semibold mt-1 capitalize">
                                    {{ $requestData->request_type }}
                                </p>
                            </div>

                        </div>

                        <div>

                            <p class="text-xs text-on-surface-variant">
                                Description
                            </p>

                            <p class="mt-1">
                                {{ $requestData->description }}
                            </p>

                        </div>

                        <div class="grid grid-cols-3 gap-4">

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Sales
                                </p>

                                <p class="font-bold mt-1">
                                    ${{ number_format($requestData->sales, 0) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Discount
                                </p>

                                <p class="font-bold mt-1">
                                    {{ $requestData->discount * 100 }}%
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Quantity
                                </p>

                                <p class="font-bold mt-1">
                                    {{ $requestData->quantity }}
                                </p>
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Region
                                </p>

                                <p class="font-semibold mt-1">
                                    {{ $requestData->region }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-on-surface-variant">
                                    Ship Mode
                                </p>

                                <p class="font-semibold mt-1">
                                    {{ $requestData->ship_mode }}
                                </p>
                            </div>

                        </div>

                    </div>

                </x-ui.card>

                {{-- RIGHT --}}
                <x-ui.card class="col-span-12 lg:col-span-5 overflow-hidden">

                    <div class="dashboard-card-header">

                        <h3 class="dashboard-title">
                            DSS Decision
                        </h3>

                    </div>

                    <div class="dashboard-card-body space-y-5">

                        {{-- Fake Result sementara --}}
                        @if($result)

                                        @php

                                            $isProfit =
                                                $result['prediction'] == 1;

                                        @endphp

                                        <div class="rounded-xl p-5 border
                            {{ $isProfit
                            ? 'border-green-200 bg-green-50'
                            : 'border-red-200 bg-red-50' }}">

                                            <div class="flex items-center gap-3 mb-4">

                                                <div class="w-12 h-12 rounded-full flex items-center justify-center
                                    {{ $isProfit
                            ? 'bg-green-100 text-green-600'
                            : 'bg-red-100 text-red-600' }}">

                                                    <span class="material-symbols-outlined material-icon-fill">

                                                        {{ $isProfit
                            ? 'check_circle'
                            : 'cancel' }}

                                                    </span>

                                                </div>

                                                <div>

                                                    <p class="text-xs uppercase tracking-wider font-bold
                                        {{ $isProfit
                            ? 'text-green-600'
                            : 'text-red-600' }}">

                                                        Prediction

                                                    </p>

                                                    <p class="text-xl font-bold
                                        {{ $isProfit
                            ? 'text-green-800'
                            : 'text-red-800' }}">

                                                        {{ $result['label_id'] }}

                                                    </p>

                                                </div>

                                            </div>

                                            {{-- Profit Probability --}}
                                            <div class="space-y-4">

                                                <div>

                                                    <div class="flex justify-between text-sm mb-1">

                                                        <span class="font-medium text-green-700">
                                                            Profit Probability
                                                        </span>

                                                        <span class="font-bold text-green-700">

                                                            {{ $result['prob_profitable'] }}%

                                                        </span>

                                                    </div>

                                                    <div class="h-2 bg-white rounded-full overflow-hidden">

                                                        <div class="h-full bg-green-500 rounded-full"
                                                            style="width: {{ $result['prob_profitable'] }}%">
                                                        </div>

                                                    </div>

                                                </div>

                                                {{-- Loss Probability --}}
                                                <div>

                                                    <div class="flex justify-between text-sm mb-1">

                                                        <span class="font-medium text-red-600">
                                                            Loss Probability
                                                        </span>

                                                        <span class="font-bold text-red-600">

                                                            {{ $result['prob_loss'] }}%

                                                        </span>

                                                    </div>

                                                    <div class="h-2 bg-white rounded-full overflow-hidden">

                                                        <div class="h-full bg-red-400 rounded-full"
                                                            style="width: {{ $result['prob_loss'] }}%">
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                        @else

                            <div class="rounded-xl p-5 border border-red-200 bg-red-50">

                                <p class="text-red-700 text-sm font-medium">

                                    DSS API tidak dapat dihubungi.

                                </p>

                            </div>

                        @endif

                        {{-- ACTION --}}
                        <div class="grid grid-cols-2 gap-4">

                            <form method="POST" action="{{ route('requests.reject', $requestData->id) }}">

                                @csrf

                                <button type="submit" class="w-full py-3 rounded-lg bg-red-500 text-white font-semibold">

                                    Reject

                                </button>

                            </form>

                            <form method="POST" action="{{ route('requests.approve', $requestData->id) }}">

                                @csrf

                                <button type="submit" class="w-full py-3 rounded-lg bg-green-600 text-white font-semibold">

                                    Approve

                                </button>

                            </form>

                        </div>

                    </div>

                </x-ui.card>

            </div>

        </div>

    </div>

@endsection