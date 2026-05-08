{{-- ── Role Badge + Header ────────────────────────────── --}}
@php
    $roleConfig = [
        'head-analytics' => ['label' => 'Head of Data Analytics & BI', 'icon' => 'analytics', 'color' => 'bg-purple-100 text-purple-700 border-purple-200'],
        'financial-controller' => ['label' => 'Financial Controller', 'icon' => 'account_balance', 'color' => 'bg-green-100 text-green-700 border-green-200'],
        'logistics-officer' => ['label' => 'Chief Logistics Officer', 'icon' => 'local_shipping', 'color' => 'bg-orange-100 text-orange-700 border-orange-200'],
        'procurement-director' => ['label' => 'Director of Strategic Procurement', 'icon' => 'inventory_2', 'color' => 'bg-blue-100 text-blue-700 border-blue-200'],
        'key-account-manager' => ['label' => 'Key Account Manager', 'icon' => 'handshake', 'color' => 'bg-cyan-100 text-cyan-700 border-cyan-200'],
    ];
    $rc = $roleConfig[$role] ?? ['label' => 'User', 'icon' => 'person', 'color' => 'bg-slate-100 text-slate-600 border-slate-200'];
@endphp

<div class="flex justify-between items-start mb-8">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $rc['color'] }}">
                <span class="material-symbols-outlined text-sm">{{ $rc['icon'] }}</span>
                {{ $rc['label'] }}
            </span>
        </div>
        <h2 class="font-display-lg text-display-lg text-on-surface">
            @if($role === 'head-analytics') Full Analytics Overview
            @elseif($role === 'financial-controller') Financial Performance
            @elseif($role === 'logistics-officer') Logistics & Distribution
            @elseif($role === 'procurement-director') Procurement Intelligence
            @elseif($role === 'key-account-manager') Key Account Overview
            @endif
        </h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
            @if($role === 'head-analytics') Akses penuh ke seluruh data analytics dan prediksi DSS.
            @elseif($role === 'financial-controller') Monitor profit, discount, dan kesehatan keuangan per
                region. Akses DSS untuk approval transaksi.
            @elseif($role === 'logistics-officer') Pantau distribusi regional dan performa pengiriman. Output
                DSS diteruskan otomatis.
            @elseif($role === 'procurement-director') Analisis kategori Technology & Furniture untuk keputusan
                pembelian strategis.
            @elseif($role === 'key-account-manager') Pantau performa segmen Corporate & Home Office untuk
                manajemen kontrak.
            @endif
        </p>
    </div>

</div>