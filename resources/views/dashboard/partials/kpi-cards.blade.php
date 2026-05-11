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
    FINANCIAL CONTROLLER — dipecah 2 baris biar tidak sesak
    ══════════════════════════════════════════════════════ --}}
@elseif($role === 'financial-controller')

    {{-- Baris 1: 3 metric keuangan utama --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <x-ui.kpi-card label="Total Profit" value="${{ number_format($summary['total_profit'] ?? 0, 0) }}"
            icon="account_balance" color="green" sub="Keuntungan bersih" />
        <x-ui.kpi-card label="Approval Rate" value="{{ $dssAnalytics['approval_rate'] ?? 0 }}%" icon="check_circle"
            color="green" :trend="($dssAnalytics['approval_rate'] ?? 0) >= 50 ? 'up' : 'down'"
            :trendVal="($dssAnalytics['approval_rate'] ?? 0) >= 50 ? 'Di atas threshold' : 'Di bawah threshold'" />
        <x-ui.kpi-card label="Rejection Rate" value="{{ $dssAnalytics['rejection_rate'] ?? 0 }}%" icon="cancel" color="red"
            :trend="($dssAnalytics['rejection_rate'] ?? 0) >= 50 ? 'down' : 'up'" :trendVal="($dssAnalytics['rejection_rate'] ?? 0) >= 50 ? 'Risiko tinggi' : 'Terkendali'" />

    </div>

    {{-- Baris 2: 2 DSS metric --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

        <x-ui.kpi-card label="Avg DSS Confidence" value="{{ $dssAnalytics['avg_confidence'] ?? 0 }}%" icon="verified"
            color="purple" :trend="($dssAnalytics['avg_confidence'] ?? 0) >= 75 ? 'up' : 'neutral'"
            trendVal="DSS confidence" />
        <x-ui.kpi-card label="Most Risky Category" value="{{ $dssAnalytics['risky_category'] ?? '-' }}" icon="warning"
            color="orange" sub="Rejection tertinggi" />

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