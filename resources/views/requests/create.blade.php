@extends('layouts.app')

@section('title', 'Create Request')

@section('content')

    @php $fields = $requestMeta['fields']; @endphp

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

                <div class="dashboard-card-header flex items-center gap-3 bg-surface-container-low/30 border-b border-outline-variant/50">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm
                        @if($requestMeta['type'] === 'procurement') bg-blue-600 text-white
                        @elseif($requestMeta['type'] === 'shipment') bg-orange-500 text-white
                        @else bg-cyan-600 text-white
                        @endif">
                        <span class="material-symbols-outlined text-xl">
                            @if($requestMeta['type'] === 'procurement') inventory_2
                            @elseif($requestMeta['type'] === 'shipment') local_shipping
                            @else handshake
                            @endif
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-on-surface uppercase tracking-widest">{{ $requestMeta['title'] }} Form</h3>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest opacity-70">Lengkapi data untuk analisis DSS AI</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('requests.store') }}" class="dashboard-card-body p-0">
                    @csrf

                    <div class="p-8 space-y-12">
                        {{-- Section 1 --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/50">
                                <span class="material-symbols-outlined text-primary text-sm">info</span>
                                <h4 class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Identitas Request</h4>
                            </div>

                            <div class="grid grid-cols-1 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">Judul Request <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">title</span>
                                        <input type="text" name="title" required value="{{ old('title') }}"
                                            placeholder="Contoh: Pengadaan Laptop Divisi IT"
                                            class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">Deskripsi Tambahan</label>
                                    <div class="relative group">
                                        <span class="material-symbols-outlined absolute left-3 top-4 text-on-surface-variant group-focus-within:text-primary transition-colors">subject</span>
                                        <textarea name="description" rows="3"
                                            placeholder="Detail kebutuhan atau catatan khusus..."
                                            class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm resize-none">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2 --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between pb-2 border-b border-outline-variant/50">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-sm">analytics</span>
                                    <h4 class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Metrik Transaksi</h4>
                                </div>
                                <span class="text-[9px] font-black text-secondary uppercase tracking-widest bg-secondary/10 px-2 py-0.5 rounded-full">Kritikal Bagi AI</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">{{ $fields['sales']['label'] }}</label>
                                    <div class="relative group">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-black text-on-surface-variant group-focus-within:text-secondary">$</span>
                                        <input type="number" name="sales" step="0.01" min="0" value="{{ old('sales', 500) }}" required
                                            class="w-full pl-8 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm font-mono focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all shadow-sm">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">{{ $fields['quantity']['label'] }}</label>
                                    <div class="relative group">
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary transition-colors">shopping_basket</span>
                                        <input type="number" name="quantity" min="1" max="20" value="{{ old('quantity', 3) }}" required
                                            class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all shadow-sm">
                                    </div>
                                </div>

                                @if($fields['discount']['show'])
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">{{ $fields['discount']['label'] }}</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary transition-colors">percent</span>
                                            <select name="discount" class="w-full pl-10 pr-10 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all shadow-sm">
                                                @foreach([0.0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8] as $d)
                                                    <option value="{{ $d }}" {{ old('discount', 0.2) == $d ? 'selected' : '' }}>{{ $d * 100 }}% Discount</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="discount" value="{{ $fields['discount']['default'] }}">
                                @endif

                                @if($fields['shipping_days']['show'])
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">{{ $fields['shipping_days']['label'] }}</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary transition-colors">schedule</span>
                                            <input type="number" name="shipping_days" min="0" max="7" value="{{ old('shipping_days', 4) }}" required
                                                class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all shadow-sm">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="shipping_days" value="{{ $fields['shipping_days']['default'] }}">
                                @endif
                            </div>
                        </div>

                        {{-- Section 3 --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/50">
                                <span class="material-symbols-outlined text-amber-600 text-sm">category</span>
                                <h4 class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Klasifikasi DSS</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                @if($fields['category']['show'])
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">Kategori Produk</label>
                                        <div class="grid grid-cols-3 gap-3">
                                            @foreach($fields['category']['options'] as $cat)
                                                <label class="relative flex flex-col items-center gap-2 p-4 border border-outline-variant rounded-2xl cursor-pointer hover:bg-surface-container-low transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-1 has-[:checked]:ring-primary group">
                                                    <input type="radio" name="category" value="{{ $cat }}" class="sr-only" {{ old('category') == $cat || (empty(old('category')) && $loop->first) ? 'checked' : '' }}>
                                                    <span class="material-symbols-outlined text-2xl text-on-surface-variant group-has-[:checked]:text-primary">
                                                        {{ $cat === 'Technology' ? 'memory' : ($cat === 'Furniture' ? 'chair' : 'inventory_2') }}
                                                    </span>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-on-surface">{{ $cat }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($fields['region']['show'])
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">Region Terkait</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">public</span>
                                            <select name="region" class="w-full pl-10 pr-10 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm">
                                                @foreach($fields['region']['options'] as $reg)
                                                    <option value="{{ $reg }}" {{ old('region') == $reg ? 'selected' : '' }}>{{ $reg }} Region</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($fields['segment']['show'])
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">Segmen Target</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">groups</span>
                                            <select name="segment" class="w-full pl-10 pr-10 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm">
                                                @foreach($fields['segment']['options'] as $seg)
                                                    <option value="{{ $seg }}" {{ old('segment') == $seg ? 'selected' : '' }}>{{ $seg }} Segment</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($fields['ship_mode']['show'])
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[10px] font-black text-on-surface uppercase tracking-widest ml-1">Mode Pengiriman</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">local_shipping</span>
                                            <select name="ship_mode" class="w-full pl-10 pr-10 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm">
                                                @foreach($fields['ship_mode']['options'] as $mode)
                                                    <option value="{{ $mode }}" {{ old('ship_mode', 'Standard Class') == $mode ? 'selected' : '' }}>{{ $mode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-surface-container-low/30 border-t border-outline-variant/50">
                        <button type="submit" class="w-full py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all flex items-center justify-center gap-3 shadow-xl active:scale-[0.98]
                            @if($requestMeta['type'] === 'procurement') bg-blue-600 hover:bg-blue-700 shadow-blue-200/50
                            @elseif($requestMeta['type'] === 'shipment') bg-orange-500 hover:bg-orange-600 shadow-orange-200/50
                            @else bg-cyan-600 hover:bg-cyan-700 shadow-cyan-200/50
                            @endif text-white">
                            <span class="material-symbols-outlined text-lg">rocket_launch</span>
                            Submit Proposal Request
                        </button>
                    </div>
                </form>

            </x-ui.card>

        </div>

    </div>

@endsection