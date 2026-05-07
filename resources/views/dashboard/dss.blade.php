@extends('layouts.app')

@section('title', 'DSS Prediksi Profit')

@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('dashboard') }}"
                    class="p-2 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-display-lg text-display-lg text-on-surface">Prediksi Profitabilitas</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                        Decision Support System — prediksi apakah sebuah transaksi akan menguntungkan.
                    </p>
                </div>
            </div>

            {{-- Error API --}}
            @if($errors->has('api'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-500">cloud_off</span>
                    <p class="text-sm text-red-700 font-medium">{{ $errors->first('api') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- ── Form Input ───────────────────────────────── --}}
                <div class="lg:col-span-3 bg-white border border-outline-variant rounded-xl overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-surface-container-low">
                        <h3 class="font-title-sm text-title-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">tune</span>
                            Parameter Transaksi
                        </h3>
                    </div>
                    <form method="POST" action="{{ route('dashboard.predict') }}" class="p-6 space-y-5">
                        @csrf

                        {{-- Angka --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    Sales ($)
                                </label>
                                <input type="number" name="sales" step="0.01" min="0"
                                    value="{{ old('sales', $input['sales'] ?? 500) }}" required
                                    class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                @error('sales')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    Quantity
                                </label>
                                <input type="number" name="quantity" min="1" max="14"
                                    value="{{ old('quantity', $input['quantity'] ?? 3) }}" required
                                    class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    Discount
                                </label>
                                <select name="discount"
                                    class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                    @foreach([0.0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8] as $d)
                                        <option value="{{ $d }}" {{ old('discount', $input['discount'] ?? 0.2) == $d ? 'selected' : '' }}>
                                            {{ ($d * 100) }}%
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    Shipping Days
                                </label>
                                <input type="number" name="shipping_days" min="0" max="7"
                                    value="{{ old('shipping_days', $input['shipping_days'] ?? 4) }}" required
                                    class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                @error('shipping_days')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Dropdown kategoris --}}
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Category
                            </label>
                            <select name="category"
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                @foreach(['Furniture', 'Office Supplies', 'Technology'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $input['category'] ?? '') === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    Segment
                                </label>
                                <select name="segment"
                                    class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                    @foreach(['Consumer', 'Corporate', 'Home Office'] as $seg)
                                        <option value="{{ $seg }}" {{ old('segment', $input['segment'] ?? '') === $seg ? 'selected' : '' }}>
                                            {{ $seg }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                    Region
                                </label>
                                <select name="region"
                                    class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                    @foreach(['East', 'West', 'Central', 'South'] as $reg)
                                        <option value="{{ $reg }}" {{ old('region', $input['region'] ?? '') === $reg ? 'selected' : '' }}>
                                            {{ $reg }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                                Ship Mode
                            </label>
                            <select name="ship_mode"
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-blue-200 focus:border-secondary transition-all">
                                @foreach(['First Class', 'Second Class', 'Standard Class', 'Same Day'] as $mode)
                                    <option value="{{ $mode }}" {{ old('ship_mode', $input['ship_mode'] ?? 'Standard Class') === $mode ? 'selected' : '' }}>
                                        {{ $mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-secondary text-white rounded-lg font-semibold hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">psychology</span>
                            Jalankan Prediksi
                        </button>
                    </form>
                </div>

                {{-- ── Hasil Prediksi ───────────────────────────── --}}
                <div class="lg:col-span-2 space-y-4">

                    @if(isset($result))
                        {{-- Hasil --}}
                        <div class="bg-white border-2 rounded-xl overflow-hidden
                            {{ $result['prediction'] == 1 ? 'border-green-400' : 'border-red-400' }}">

                            {{-- Header hasil --}}
                            <div class="p-5 {{ $result['prediction'] == 1 ? 'bg-green-50' : 'bg-red-50' }}">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center
                                        {{ $result['prediction'] == 1 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        <span class="material-symbols-outlined text-2xl"
                                            style="font-variation-settings:'FILL' 1">
                                            {{ $result['prediction'] == 1 ? 'check_circle' : 'cancel' }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider
                                            {{ $result['prediction'] == 1 ? 'text-green-600' : 'text-red-500' }}">
                                            Hasil Prediksi
                                        </p>
                                        <p class="text-xl font-bold
                                            {{ $result['prediction'] == 1 ? 'text-green-800' : 'text-red-800' }}">
                                            {{ $result['label_id'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail probabilitas --}}
                            <div class="p-5 space-y-4">
                                {{-- Prob untung --}}
                                <div>
                                    <div class="flex justify-between text-sm mb-1.5">
                                        <span class="font-semibold text-green-700">Probabilitas Untung</span>
                                        <span class="font-bold text-green-700">{{ $result['prob_profitable'] }}%</span>
                                    </div>
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full transition-all"
                                            style="width: {{ $result['prob_profitable'] }}%"></div>
                                    </div>
                                </div>
                                {{-- Prob rugi --}}
                                <div>
                                    <div class="flex justify-between text-sm mb-1.5">
                                        <span class="font-semibold text-red-600">Probabilitas Rugi</span>
                                        <span class="font-bold text-red-600">{{ $result['prob_loss'] }}%</span>
                                    </div>
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-400 rounded-full transition-all"
                                            style="width: {{ $result['prob_loss'] }}%"></div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                    <span class="text-sm text-on-surface-variant">Tingkat Keyakinan</span>
                                    <span class="text-sm font-bold px-3 py-1 rounded-full
                                        @if($result['confidence'] === 'Sangat Tinggi') bg-green-100 text-green-700
                                        @elseif($result['confidence'] === 'Tinggi') bg-blue-100 text-blue-700
                                        @elseif($result['confidence'] === 'Sedang') bg-amber-100 text-amber-700
                                        @else bg-slate-100 text-slate-600 @endif">
                                        {{ $result['confidence'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Input yang digunakan --}}
                        <div class="bg-white border border-outline-variant rounded-xl p-4">
                            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-3">Input yang
                                Digunakan</p>
                            <div class="space-y-1.5">
                                @foreach(['sales' => 'Sales', 'quantity' => 'Quantity', 'discount' => 'Discount', 'shipping_days' => 'Shipping Days', 'category' => 'Category', 'segment' => 'Segment', 'region' => 'Region', 'ship_mode' => 'Ship Mode'] as $key => $label)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-on-surface-variant">{{ $label }}</span>
                                        <span class="font-semibold">
                                            @if($key === 'sales') ${{ number_format($input[$key], 2) }}
                                            @elseif($key === 'discount') {{ ($input[$key] * 100) }}%
                                            @else {{ $input[$key] }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @else
                        {{-- Placeholder sebelum prediksi --}}
                        <div class="bg-white border border-outline-variant rounded-xl p-8 text-center">
                            <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl text-blue-400"
                                    style="font-variation-settings:'FILL' 1">psychology</span>
                            </div>
                            <p class="font-semibold text-on-surface mb-2">Belum ada prediksi</p>
                            <p class="text-sm text-on-surface-variant">Isi form di sebelah kiri dan klik <strong>Jalankan
                                    Prediksi</strong> untuk melihat hasil analisis model Machine Learning.</p>
                        </div>

                        {{-- Info model --}}
                        <div class="bg-primary-container rounded-xl p-5 text-white relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 opacity-10">
                                <span class="material-symbols-outlined" style="font-size:100px">model_training</span>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Tentang Model DSS</p>
                            <div class="space-y-2 text-sm text-slate-300 relative z-10">
                                <p>🤖 Algoritma: <span class="text-white font-semibold">Random Forest</span></p>
                                <p>📊 Dataset: <span class="text-white font-semibold">9.994 transaksi</span></p>
                                <p>🎯 Target: <span class="text-white font-semibold">is_profitable</span></p>
                                <p>⚙️ Fitur: <span class="text-white font-semibold">8 variabel</span></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection