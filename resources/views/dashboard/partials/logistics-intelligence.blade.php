<x-ui.intelligence-card title="Logistics Intelligence" subtitle="DSS-driven shipment & operational risk analysis."
    icon="local_shipping" color="orange" :stats="[
        [
            'label' => 'Shipment Approval Rate',
            'value' => $logisticsAnalytics['approval_rate'] . '%',
            'color' => $logisticsAnalytics['approval_rate'] >= 50 ? 'green' : 'red',
            'sub' => $logisticsAnalytics['approval_rate'] >= 50 ? 'Operasional normal' : 'Risiko meningkat',
        ],
        [
            'label' => 'Most Risky Ship Mode',
            'value' => $logisticsAnalytics['risky_ship_mode'],
            'color' => 'red',
            'sub' => 'Perlu monitoring',
        ],
    ]" :insights="$logisticsInsights" />