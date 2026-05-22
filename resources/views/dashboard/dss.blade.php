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
                <div class="lg:col-span-3 bg-surface-container-lowest dark:bg-white/[0.02] backdrop-blur-md border border-outline-variant/50 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-outline-variant/30 bg-surface-container-low/50 dark:bg-white/[0.05]">
                        <h3 class="font-black text-sm uppercase tracking-widest text-on-surface flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-base">tune</span>
                            </div>
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
                            class="w-full py-4 bg-secondary text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-secondary/90 transition-all flex items-center justify-center gap-3 shadow-lg shadow-secondary/20 hover:scale-[1.02] active:scale-[0.98]">
                            <span class="material-symbols-outlined text-lg">psychology</span>
                            Jalankan Prediksi
                        </button>
                    </form>
                </div>

                {{-- ── Hasil Prediksi ───────────────────────────── --}}
                <div class="lg:col-span-2 space-y-4">

                    @if(isset($result))
                        {{-- Hasil --}}
                        <div class="bg-surface-container-lowest dark:bg-white/[0.02] backdrop-blur-md border border-outline-variant/50 rounded-2xl overflow-hidden shadow-xl">

                            {{-- Header hasil --}}
                            <div class="p-6 {{ $result['prediction'] == 1 ? 'bg-green-50/50 dark:bg-green-500/10' : 'bg-red-50/50 dark:bg-red-500/10' }} border-b border-outline-variant/30">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm
                                        {{ $result['prediction'] == 1 ? 'bg-white dark:bg-green-500/20 text-green-600' : 'bg-white dark:bg-red-500/20 text-red-600' }}">
                                        <span class="material-symbols-outlined text-3xl"
                                            style="font-variation-settings:'FILL' 1">
                                            {{ $result['prediction'] == 1 ? 'check_circle' : 'cancel' }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em]
                                            {{ $result['prediction'] == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                            Hasil Prediksi
                                        </p>
                                        <p class="text-2xl font-black
                                            {{ $result['prediction'] == 1 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                            {{ $result['label_id'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail probabilitas --}}
                            <div class="p-6 space-y-6">
                                {{-- Prob untung --}}
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-green-700 dark:text-green-400">Probabilitas Untung</span>
                                        <span class="text-lg font-black text-green-700 dark:text-green-400">{{ $result['prob_profitable'] }}%</span>
                                    </div>
                                    <div class="h-2.5 bg-surface-container rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-green-500/80 to-green-500 rounded-full transition-all duration-1000"
                                            style="width: {{ $result['prob_profitable'] }}%"></div>
                                    </div>
                                </div>
                                {{-- Prob rugi --}}
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-red-600 dark:text-red-400">Probabilitas Rugi</span>
                                        <span class="text-lg font-black text-red-600 dark:text-red-400">{{ $result['prob_loss'] }}%</span>
                                    </div>
                                    <div class="h-2.5 bg-surface-container rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-red-400/80 to-red-400 rounded-full transition-all duration-1000"
                                            style="width: {{ $result['prob_loss'] }}%"></div>
                                    </div>
                                </div>

                                <div class="pt-5 border-t border-outline-variant/30 flex justify-between items-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Tingkat Keyakinan</span>
                                    <span class="text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-sm
                                        @if($result['confidence'] === 'Sangat Tinggi') bg-green-500/10 text-green-600 dark:text-green-400
                                        @elseif($result['confidence'] === 'Tinggi') bg-blue-500/10 text-blue-600 dark:text-blue-400
                                        @elseif($result['confidence'] === 'Sedang') bg-amber-500/10 text-amber-600 dark:text-amber-400
                                        @else bg-surface-container-high text-on-surface-variant @endif">
                                        {{ $result['confidence'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Input yang digunakan --}}
                        <div class="bg-surface-container-lowest dark:bg-white/[0.02] border border-outline-variant/50 rounded-2xl p-5">
                            <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary/40"></span>
                                Input yang Digunakan
                            </p>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                @foreach(['sales' => 'Sales', 'quantity' => 'Quantity', 'discount' => 'Discount', 'shipping_days' => 'Shipping Days', 'category' => 'Category', 'segment' => 'Segment', 'region' => 'Region', 'ship_mode' => 'Ship Mode'] as $key => $label)
                                    <div class="flex justify-between items-center text-xs py-1.5 border-b border-outline-variant/10 last:border-0">
                                        <span class="text-on-surface-variant font-medium">{{ $label }}</span>
                                        <span class="font-bold text-on-surface">
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
                        <div class="bg-surface-container-lowest dark:bg-white/[0.02] border border-outline-variant/50 rounded-2xl p-10 text-center shadow-sm">
                            <div class="w-20 h-20 rounded-3xl bg-secondary/10 dark:bg-secondary/20 flex items-center justify-center mx-auto mb-6 rotate-3">
                                <span class="material-symbols-outlined text-4xl text-secondary"
                                    style="font-variation-settings:'FILL' 1">psychology</span>
                            </div>
                            <p class="font-black text-on-surface uppercase tracking-widest mb-3">Belum ada prediksi</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed px-4">Isi form di sebelah kiri dan klik <span class="font-black text-secondary">Jalankan Prediksi</span> untuk melihat hasil analisis model Machine Learning.</p>
                        </div>

                        {{-- Info model --}}
                        <div class="bg-primary-container rounded-xl p-5 text-white relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 opacity-10">
                                <span class="material-symbols-outlined" style="font-size:100px">model_training</span>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Tentang Model DSS</p>
                            <div class="space-y-3 text-sm text-slate-300 relative z-10">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-blue-400">smart_toy</span>
                                    <span>Algoritma: <span class="text-white font-semibold">Random Forest</span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-green-400">dataset</span>
                                    <span>Dataset: <span class="text-white font-semibold">9.994 transaksi</span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-red-400">track_changes</span>
                                    <span>Target: <span class="text-white font-semibold">is_profitable</span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-amber-400">manufacturing</span>
                                    <span>Fitur: <span class="text-white font-semibold">8 variabel</span></span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection