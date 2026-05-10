<x-ui.intelligence-card title="Procurement Intelligence" subtitle="DSS-driven procurement & supply risk analysis."
    icon="inventory_2" color="blue" :stats="[
        [
            'label' => 'Procurement Approval Rate',
            'value' => $procurementAnalytics['approval_rate'] . '%',
            'color' => $procurementAnalytics['approval_rate'] >= 50 ? 'green' : 'red',
            'sub' => $procurementAnalytics['approval_rate'] >= 50 ? 'Di atas target' : 'Perlu evaluasi',
        ],
        [
            'label' => 'Most Rejected Category',
            'value' => $procurementAnalytics['risky_category'],
            'color' => 'red',
            'sub' => 'Prioritas review',
        ],
    ]" :insights="$procurementInsights" />