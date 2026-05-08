<div class="flex justify-end mb-6">

    <a href="{{ route('requests.create') }}" class="inline-flex items-center gap-2
        px-4 py-2 rounded-lg
        bg-secondary text-white
        text-sm font-semibold hover:opacity-90 transition">

        <span class="material-symbols-outlined">
            add
        </span>

        Create Request

    </a>
</div>

@include('dashboard.partials.procurement-intelligence')

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Profit: Technology & Furniture
            </h3>

            <p class="dashboard-subtitle">
                Dua kategori utama yang kamu kelola
            </p>

        </div>

        <div class="dashboard-card-body h-64">
            <canvas id="categoryChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Detail Kategori
            </h3>

        </div>

        <div class="dashboard-card-body">

            <div class="space-y-4">

                @foreach($category as $cat)

                            <div class="p-4 border border-outline-variant rounded-lg">

                                <div class="flex justify-between items-start mb-3">

                                    <div>

                                        <p class="font-semibold text-on-surface">
                                            {{ $cat['category'] }}
                                        </p>

                                        <p class="text-xs text-on-surface-variant">
                                            Avg margin:
                                            {{ $cat['avg_margin'] ?? '-' }}%
                                        </p>

                                    </div>

                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2 py-1 rounded-full
                                                                                                                                {{ $cat['category'] === 'Technology'
                    ? 'bg-blue-50 text-blue-700'
                    : 'bg-amber-50 text-amber-700' }}">

                                        <span class="material-symbols-outlined material-icon text-sm">

                                            {{ $cat['category'] === 'Technology'
                    ? 'memory'
                    : 'chair' }}

                                        </span>

                                        {{ $cat['category'] }}

                                    </span>

                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm">

                                    <div>

                                        <p class="text-on-surface-variant text-xs">
                                            Total Sales
                                        </p>

                                        <p class="font-bold">
                                            ${{ number_format($cat['total_sales'], 0) }}
                                        </p>

                                    </div>

                                    <div>

                                        <p class="text-on-surface-variant text-xs">
                                            Total Profit
                                        </p>

                                        <p class="font-bold text-green-600">
                                            ${{ number_format($cat['total_profit'], 0) }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                @endforeach

            </div>

        </div>

    </x-ui.card>

</div>

@include('dashboard.partials.top-products')

<x-ui.card class="mt-6 overflow-hidden bg-blue-50 border-blue-200">

    <div class="dashboard-card-body">

        <div class="flex items-start gap-4">

            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">

                <span class="material-symbols-outlined material-icon-fill">
                    psychology
                </span>

            </div>

            <div>

                <p class="font-semibold text-blue-900 mb-1">
                    Rekomendasi DSS untuk Procurement
                </p>

                <p class="text-sm text-blue-700 leading-relaxed">

                    DSS akan memberikan rekomendasi harga beli maksimal
                    agar produk yang dibeli dari manufaktur tetap bisa
                    dijual dengan margin positif.

                    Rekomendasi ini dikirimkan oleh
                    Financial Controller.

                </p>

            </div>

        </div>

    </div>

</x-ui.card>

{{-- Intellegence Feed --}}
@include('dashboard.partials.intelligence-feed')