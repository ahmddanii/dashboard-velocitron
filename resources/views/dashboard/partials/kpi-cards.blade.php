@php $role = $role ?? 'head-analytics'; @endphp

{{-- ══════════════════════════════════════════════════════
HEAD OF DATA ANALYTICS
══════════════════════════════════════════════════════ --}}
@if($role === 'head-analytics')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <x-ui.kpi-card label="Total Sales" value="${{ number_format($summary['total_sales'] ?? 0, 0) }}" icon="payments"
            color="blue" sub="Semua periode" />
        <x-ui.kpi-card label="Prediction Volume" value="{{ $analyticsMonitoring['prediction_volume'] ?? 0 }}"
            icon="model_training" color="purple" sub="Total DSS requests" />
        <x-ui.kpi-card label="Avg Confidence" value="{{ $analyticsMonitoring['avg_confidence'] ?? 0 }}%" icon="verified"
            color="green" :trend="($analyticsMonitoring['avg_confidence'] ?? 0) >= 75 ? 'up' : 'down'"
            :trendVal="($analyticsMonitoring['avg_confidence'] ?? 0) >= 75 ? 'Model stabil' : 'Perlu monitoring'" />
        <x-ui.kpi-card label="DSS Health" value="{{ $analyticsMonitoring['health_status'] ?? 'Unknown' }}"
            icon="monitor_heart" :color="($analyticsMonitoring['health_status'] ?? '') === 'Stable' ? 'green' : 'orange'"
            :sub="'Est. accuracy ' . ($analyticsMonitoring['estimated_accuracy'] ?? 0) . '%'" />

    </div>

    {{-- ══════════════════════════════════════════════════════
    FINANCIAL CONTROLLER — 2 baris × 4 kolom, metric lengkap
    ══════════════════════════════════════════════════════ --}}
@elseif($role === 'financial-controller')

    {{-- Baris 1: Core Financials --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        <x-ui.kpi-card label="Total Profit" value="${{ number_format($summary['total_profit'] ?? 0, 0) }}"
            icon="account_balance" color="green" sub="Keuntungan bersih" />
        <x-ui.kpi-card label="Total Sales" value="${{ number_format($summary['total_sales'] ?? 0, 0) }}"
            icon="payments" color="blue" sub="Revenue keseluruhan" />
        <x-ui.kpi-card label="Total Requests" value="{{ $dssAnalytics['total_predictions'] ?? 0 }}"
            icon="analytics" color="purple" sub="Volume transaksi DSS" />
        <x-ui.kpi-card label="Avg Profit Margin" value="{{ $summary['avg_profit_pct'] ?? 0 }}%"
            icon="percent" color="emerald" sub="Margin keseluruhan" />

    </div>

    {{-- Baris 2: DSS & Risk Metrics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <x-ui.kpi-card label="Approval Rate" value="{{ $dssAnalytics['approval_rate'] ?? 0 }}%" icon="check_circle"
            color="green" :trend="($dssAnalytics['approval_rate'] ?? 0) >= 50 ? 'up' : 'down'"
            :trendVal="($dssAnalytics['approval_rate'] ?? 0) >= 50 ? 'Stabil' : 'Menurun'" />
        <x-ui.kpi-card label="Rejection Rate" value="{{ $dssAnalytics['rejection_rate'] ?? 0 }}%" icon="cancel" color="red"
            :trend="($dssAnalytics['rejection_rate'] ?? 0) >= 30 ? 'down' : 'up'" :trendVal="($dssAnalytics['rejection_rate'] ?? 0) >= 30 ? 'Risiko tinggi' : 'Terkendali'" />
        <x-ui.kpi-card label="Avg Discount" value="{{ $summary['avg_discount'] ?? 0 }}%"
            icon="sell" color="orange"
            :trend="($summary['avg_discount'] ?? 0) >= 20 ? 'down' : 'up'"
            :trendVal="($summary['avg_discount'] ?? 0) >= 20 ? 'Tinggi' : 'Normal'" />
        <x-ui.kpi-card label="Most Risky Category" value="{{ $dssAnalytics['risky_category'] ?? '-' }}" icon="warning"
            color="amber" sub="Rejection tertinggi" />

    </div>

    {{-- ══════════════════════════════════════════════════════
    LOGISTICS OFFICER
    ══════════════════════════════════════════════════════ --}}
@elseif($role === 'logistics-officer')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <x-ui.kpi-card label="Total Orders" value="{{ number_format($summary['total_orders'] ?? 0) }}" icon="shopping_cart"
            color="blue" sub="Semua periode" />
        <x-ui.kpi-card label="Shipment Approval Rate" value="{{ $logisticsAnalytics['approval_rate'] ?? 0 }}%"
            icon="local_shipping" color="green" :trend="($logisticsAnalytics['approval_rate'] ?? 0) >= 50 ? 'up' : 'down'"
            :trendVal="($logisticsAnalytics['approval_rate'] ?? 0) >= 50 ? 'Distribusi normal' : 'Risiko meningkat'" />
        <x-ui.kpi-card label="Most Risky Ship Mode" value="{{ $logisticsAnalytics['risky_ship_mode'] ?? '-' }}"
            icon="warning" color="red" sub="Rejection tertinggi" />
        <x-ui.kpi-card label="Avg Profit Margin" value="{{ $summary['avg_profit_pct'] ?? 0 }}%" icon="percent"
            color="purple" sub="Margin keseluruhan" />

    </div>

    {{-- ══════════════════════════════════════════════════════
    PROCUREMENT DIRECTOR
    ══════════════════════════════════════════════════════ --}}
@elseif($role === 'procurement-director')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <x-ui.kpi-card label="Total Sales" value="${{ number_format($summary['total_sales'] ?? 0, 0) }}" icon="payments"
            color="blue" sub="Semua periode" />
        <x-ui.kpi-card label="Procurement Approval Rate" value="{{ $procurementAnalytics['approval_rate'] ?? 0 }}%"
            icon="inventory_2" color="green" :trend="($procurementAnalytics['approval_rate'] ?? 0) >= 50 ? 'up' : 'down'"
            :trendVal="($procurementAnalytics['approval_rate'] ?? 0) >= 50 ? 'Supply aman' : 'Perlu evaluasi'" />
        <x-ui.kpi-card label="Most Rejected Category" value="{{ $procurementAnalytics['risky_category'] ?? '-' }}"
            icon="warning" color="red" sub="Prioritas review" />
        <x-ui.kpi-card label="Avg Profit Margin" value="{{ $summary['avg_profit_pct'] ?? 0 }}%" icon="percent"
            color="purple" sub="Target margin" />

    </div>

    {{-- ══════════════════════════════════════════════════════
    KEY ACCOUNT MANAGER
    ══════════════════════════════════════════════════════ --}}
@elseif($role === 'key-account-manager')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <x-ui.kpi-card label="Contract Approval Rate" value="{{ $kamAnalytics['approval_rate'] ?? 0 }}%" icon="handshake"
            color="cyan" :trend="($kamAnalytics['approval_rate'] ?? 0) >= 50 ? 'up' : 'down'"
            :trendVal="($kamAnalytics['approval_rate'] ?? 0) >= 50 ? 'Kontrak sehat' : 'Perlu review'" />
        <x-ui.kpi-card label="Most Profitable Segment" value="{{ $kamAnalytics['top_segment'] ?? '-' }}" icon="groups"
            color="blue" sub="Segment terkuat" />
        <x-ui.kpi-card label="Strongest Region" value="{{ $kamAnalytics['top_region'] ?? '-' }}" icon="location_on"
            color="purple" sub="Sales dominan" />
        <x-ui.kpi-card label="Total Profit" value="${{ number_format($summary['total_profit'] ?? 0, 0) }}"
            icon="trending_up" color="green" sub="Keuntungan bersih" />

    </div>

@endif