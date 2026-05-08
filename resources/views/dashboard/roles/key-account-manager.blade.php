@include('dashboard.partials.kam-intelligence')

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

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Sales: Corporate & Home Office
            </h3>

            <p class="dashboard-subtitle">
                Segmen yang kamu kelola
            </p>

        </div>

        <div class="dashboard-card-body h-64">
            <canvas id="segmentChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Detail Segmen
            </h3>

        </div>

        <div class="dashboard-card-body">

            <div class="space-y-4">

                @foreach($segment as $seg)

                            <div class="p-4 border border-outline-variant rounded-lg">

                                <div class="flex justify-between items-center mb-3">

                                    <p class="font-semibold text-on-surface">
                                        {{ $seg['segment'] }}
                                    </p>

                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2 py-1 rounded-full
                                                                                                        {{ $seg['segment'] === 'Corporate'
                    ? 'bg-blue-50 text-blue-700'
                    : 'bg-cyan-50 text-cyan-700' }}">

                                        <span class="material-symbols-outlined material-icon text-sm">

                                            {{ $seg['segment'] === 'Corporate'
                    ? 'apartment'
                    : 'home_work' }}

                                        </span>

                                        {{ $seg['segment'] }}

                                    </span>

                                </div>

                                <div class="grid grid-cols-3 gap-2 text-sm">

                                    <div>

                                        <p class="text-on-surface-variant text-xs">
                                            Sales
                                        </p>

                                        <p class="font-bold">
                                            ${{ number_format($seg['total_sales'], 0) }}
                                        </p>

                                    </div>

                                    <div>

                                        <p class="text-on-surface-variant text-xs">
                                            Profit
                                        </p>

                                        <p class="font-bold text-green-600">
                                            ${{ number_format($seg['total_profit'], 0) }}
                                        </p>

                                    </div>

                                    <div>

                                        <p class="text-on-surface-variant text-xs">
                                            Customers
                                        </p>

                                        <p class="font-bold">
                                            {{ number_format($seg['total_customers'] ?? 0) }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                @endforeach

            </div>

        </div>

    </x-ui.card>

</div>

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-7 overflow-hidden">

        <div class="dashboard-card-header">

            <h3 class="dashboard-title">
                Distribusi per Region
            </h3>

        </div>

        <div class="dashboard-card-body h-56">
            <canvas id="regionChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-5 overflow-hidden bg-cyan-50 border-cyan-200">

        <div class="dashboard-card-body flex flex-col justify-center h-full">

            <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center text-cyan-600 mb-4">

                <span class="material-symbols-outlined material-icon-fill">
                    handshake
                </span>

            </div>

            <p class="font-semibold text-cyan-900 mb-3">
                Alur Approval Kontrak
            </p>

            <ol class="text-sm text-cyan-700 space-y-2 list-none">

                <li class="flex items-start gap-2">

                    <span
                        class="w-5 h-5 rounded-full bg-cyan-200 text-cyan-800 text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">
                        1
                    </span>

                    Kamu ajukan draf kontrak klien

                </li>

                <li class="flex items-start gap-2">

                    <span
                        class="w-5 h-5 rounded-full bg-cyan-200 text-cyan-800 text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">
                        2
                    </span>

                    Financial Controller input ke DSS

                </li>

                <li class="flex items-start gap-2">

                    <span
                        class="w-5 h-5 rounded-full bg-cyan-200 text-cyan-800 text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">
                        3
                    </span>

                    DSS prediksi profitabilitas kontrak

                </li>

                <li class="flex items-start gap-2">

                    <span
                        class="w-5 h-5 rounded-full bg-cyan-200 text-cyan-800 text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">
                        4
                    </span>

                    Kamu terima hasil:
                    <strong class="ml-1">
                        Approved / Rejected
                    </strong>

                </li>

            </ol>

        </div>

    </x-ui.card>

</div>

{{-- Intellegence Feed --}}
@include('dashboard.partials.intelligence-feed')