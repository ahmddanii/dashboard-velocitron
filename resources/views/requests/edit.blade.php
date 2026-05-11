@extends('layouts.app')

@section('title', 'Edit Request')

@section('content')

    @php $fields = $requestMeta['fields']; @endphp

    <div class="p-6">

        <div class="max-w-4xl mx-auto">

            <div class="flex items-center gap-4 mb-8">

                <a href="{{ route('transactions.history') }}" class="p-2 rounded-lg border border-outline-variant">

                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>

                </a>

                <div>

                    <h2 class="font-display-lg text-display-lg">
                        Edit {{ $requestMeta['title'] }}
                    </h2>

                    <p class="text-on-surface-variant mt-1">

                        {{ $requestMeta['description'] }}

                    </p>

                </div>

            </div>

            <x-ui.card class="overflow-hidden">

                <div class="dashboard-card-header flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                        @if($requestMeta['type'] === 'procurement') bg-blue-50 text-blue-600
                        @elseif($requestMeta['type'] === 'shipment') bg-orange-50 text-orange-600
                        @else bg-cyan-50 text-cyan-600
                        @endif">
                        <span class="material-symbols-outlined text-base">
                            edit
                        </span>
                    </div>
                    <div>
                        <h3 class="dashboard-title">Update Form</h3>
                        <p class="dashboard-subtitle">
                            Perbarui detail request kamu sebelum diproses.
                        </p>
                    </div>
                </div>

                @if ($errors->any())

                    <div class="mx-4 mt-4 p-4 rounded-lg bg-red-50 border border-red-200">

                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-red-600 text-base">error</span>
                            <p class="text-sm font-semibold text-red-700">Terdapat kesalahan pada form:</p>
                        </div>

                        <ul class="text-sm text-red-700 space-y-1 ml-6">

                            @foreach ($errors->all() as $error)

                                <li>
                                    • {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <form method="POST" action="{{ route('requests.update', $requestItem->id) }}" class="dashboard-card-body space-y-5">

                    @csrf
                    @method('PUT')

                    {{-- ═══════════════════════════════════════════
                        SECTION 1: Request Info
                    ═══════════════════════════════════════════ --}}
                    <div class="border-b border-outline-variant pb-5">
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">description</span>
                            Request Information
                        </p>

                        {{-- Title --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Title
                            </label>
                            <input type="text" name="title" required value="{{ old('title', $requestItem->title) }}"
                                class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                       focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Description
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                       focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">{{ old('description', $requestItem->description) }}</textarea>
                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════
                        SECTION 2: Transaction Details
                    ═══════════════════════════════════════════ --}}
                    <div class="border-b border-outline-variant pb-5">
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">payments</span>
                            Transaction Details
                        </p>

                        <div class="grid grid-cols-2 gap-4">

                            {{-- Sales --}}
                            <div>
                                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    {{ $fields['sales']['label'] }}
                                </label>
                                <input type="number" name="sales" step="0.01" min="0" value="{{ old('sales', $requestItem->sales) }}" required
                                    class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                           focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                            </div>

                            {{-- Quantity --}}
                            <div>
                                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    {{ $fields['quantity']['label'] }}
                                </label>
                                <input type="number" name="quantity" min="1" max="20" value="{{ old('quantity', $requestItem->quantity) }}" required
                                    class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                           focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">

                            {{-- Discount --}}
                            @if($fields['discount']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $fields['discount']['label'] }}
                                    </label>
                                    <select name="discount"
                                        class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                               focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                                        @foreach([0.0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8] as $d)
                                            <option value="{{ $d }}" {{ old('discount', $requestItem->discount) == $d ? 'selected' : '' }}>
                                                {{ $d * 100 }}%
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="discount" value="{{ $requestItem->discount }}">
                            @endif

                            {{-- Shipping Days --}}
                            @if($fields['shipping_days']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $fields['shipping_days']['label'] }}
                                    </label>
                                    <input type="number" name="shipping_days" min="0" max="7"
                                        value="{{ old('shipping_days', $requestItem->shipping_days) }}" required
                                        class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                               focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                                </div>
                            @else
                                <input type="hidden" name="shipping_days" value="{{ $requestItem->shipping_days }}">
                            @endif

                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════
                        SECTION 3: Classification
                    ═══════════════════════════════════════════ --}}
                    <div class="pb-2">
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">category</span>
                            Classification
                        </p>

                        {{-- Category --}}
                        @if($fields['category']['show'])
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    {{ $fields['category']['label'] }}
                                </label>
                                <select name="category"
                                    class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                           focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                                    @foreach($fields['category']['options'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $requestItem->category) == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">

                            {{-- Segment --}}
                            @if($fields['segment']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $fields['segment']['label'] }}
                                    </label>
                                    <select name="segment"
                                        class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                               focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                                        @foreach($fields['segment']['options'] as $seg)
                                            <option value="{{ $seg }}" {{ old('segment', $requestItem->segment) == $seg ? 'selected' : '' }}>
                                                {{ $seg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="segment" value="{{ $requestItem->segment }}">
                            @endif

                            {{-- Region --}}
                            @if($fields['region']['show'])
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                        {{ $fields['region']['label'] }}
                                    </label>
                                    <select name="region"
                                        class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                               focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                                        @foreach($fields['region']['options'] as $reg)
                                            <option value="{{ $reg }}" {{ old('region', $requestItem->region) == $reg ? 'selected' : '' }}>
                                                {{ $reg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div>

                        {{-- Ship Mode --}}
                        @if($fields['ship_mode']['show'])
                            <div class="mt-4">
                                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    {{ $fields['ship_mode']['label'] }}
                                </label>
                                <select name="ship_mode"
                                    class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm
                                           focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                                    @foreach($fields['ship_mode']['options'] as $mode)
                                        <option value="{{ $mode }}" {{ old('ship_mode', $requestItem->ship_mode) == $mode ? 'selected' : '' }}>
                                            {{ $mode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="ship_mode" value="{{ $requestItem->ship_mode }}">
                        @endif
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3">
                        <a href="{{ route('transactions.history') }}"
                            class="flex-1 py-3 rounded-lg font-semibold text-center border border-outline-variant text-on-surface hover:bg-surface-container-low transition-all">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-[2] py-3 rounded-lg font-semibold transition-all flex items-center justify-center gap-2
                                @if($requestMeta['type'] === 'procurement') bg-blue-600 hover:bg-blue-700
                                @elseif($requestMeta['type'] === 'shipment') bg-orange-500 hover:bg-orange-600
                                @else bg-cyan-600 hover:bg-cyan-700
                                @endif text-white">

                            <span class="material-symbols-outlined text-sm">
                                save
                            </span>

                            Simpan Perubahan

                        </button>
                    </div>

                </form>

            </x-ui.card>

        </div>

    </div>

@endsection
