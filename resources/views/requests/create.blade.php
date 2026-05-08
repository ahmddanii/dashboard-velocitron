@extends('layouts.app')

@section('title', 'Create Request')

@section('content')

    <div class="p-6">

        <div class="max-w-4xl mx-auto">

            <div class="flex items-center gap-4 mb-8">

                <a href="{{ route('dashboard') }}" class="p-2 rounded-lg border border-outline-variant">

                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>

                </a>

                <div>

                    <h2 class="font-display-lg text-display-lg">
                        {{ $requestMeta['title'] }}
                    </h2>

                    <p class="text-on-surface-variant mt-1">

                        {{ $requestMeta['description'] }}

                    </p>

                </div>

            </div>

            <x-ui.card class="overflow-hidden">

                <div class="dashboard-card-header">

                    <h3 class="dashboard-title">
                        Request Form
                    </h3>

                </div>

                @if ($errors->any())

                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">

                        <ul class="text-sm text-red-700 space-y-1">

                            @foreach ($errors->all() as $error)

                                <li>
                                    • {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <form method="POST" action="{{ route('requests.store') }}" class="dashboard-card-body space-y-5">

                    @csrf

                    @if ($errors->any())

                        <div class="p-4 rounded-xl bg-red-50 border border-red-200">

                            <ul class="text-sm text-red-700 space-y-1">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        • {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    {{-- Title --}}
                    <div>

                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Request Title
                        </label>

                        <input type="text" name="title" required value="{{ old('title') }}"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                    </div>

                    {{-- Description --}}
                    <div>

                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Description
                        </label>

                        <textarea name="description" rows="4"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">{{ old('description') }}</textarea>

                    </div>

                    {{-- Numeric Inputs --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Sales ($)
                            </label>

                            <input type="number" name="sales" step="0.01" min="0" value="{{ old('sales', 500) }}" required
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                        </div>

                        <div>

                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Quantity
                            </label>

                            <input type="number" name="quantity" min="1" max="20" value="{{ old('quantity', 3) }}" required
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                        </div>

                    </div>

                    {{-- Discount + Shipping --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Discount
                            </label>

                            <select name="discount"
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                                @foreach([0.0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8] as $d)

                                    <option value="{{ $d }}" {{ old('discount', 0.2) == $d ? 'selected' : '' }}>

                                        {{ $d * 100 }}%

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div>

                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Shipping Days
                            </label>

                            <input type="number" name="shipping_days" min="0" max="7" value="{{ old('shipping_days', 4) }}"
                                required
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                        </div>

                    </div>

                    {{-- Category --}}
                    <div>

                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Category
                        </label>

                        <select name="category"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                            @foreach(['Furniture', 'Office Supplies', 'Technology'] as $cat)

                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>

                                    {{ $cat }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Segment + Region --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Segment
                            </label>

                            <select name="segment"
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                                @foreach(['Consumer', 'Corporate', 'Home Office'] as $seg)

                                    <option value="{{ $seg }}" {{ old('segment') == $seg ? 'selected' : '' }}>

                                        {{ $seg }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div>

                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Region
                            </label>

                            <select name="region"
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                                @foreach(['East', 'West', 'Central', 'South'] as $reg)

                                    <option value="{{ $reg }}" {{ old('region') == $reg ? 'selected' : '' }}>

                                        {{ $reg }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    {{-- Ship Mode --}}
                    <div>

                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Ship Mode
                        </label>

                        <select name="ship_mode"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm">

                            @foreach(['First Class', 'Second Class', 'Standard Class', 'Same Day'] as $mode)

                                <option value="{{ $mode }}" {{ old('ship_mode', 'Standard Class') == $mode ? 'selected' : '' }}>

                                    {{ $mode }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-3 bg-secondary text-white rounded-lg font-semibold hover:bg-blue-700 transition-all flex items-center justify-center gap-2">

                        <span class="material-symbols-outlined text-sm">
                            send
                        </span>

                        Submit Request

                    </button>

                </form>

            </x-ui.card>

        </div>

    </div>

@endsection