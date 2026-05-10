<x-ui.intelligence-card title="Client Intelligence" subtitle="DSS-driven client profitability & contract analysis."
    icon="handshake" color="cyan" :stats="[
        [
            'label' => 'Contract Approval Rate',
            'value' => $kamAnalytics['approval_rate'] . '%',
            'color' => $kamAnalytics['approval_rate'] >= 50 ? 'green' : 'red',
            'sub' => $kamAnalytics['approval_rate'] >= 50 ? 'Di atas target' : 'Di bawah target',
        ],
        [
            'label' => 'Most Profitable Segment',
            'value' => $kamAnalytics['top_segment'],
            'color' => 'blue',
        ],
        [
            'label' => 'Strongest Sales Region',
            'value' => $kamAnalytics['top_region'],
            'color' => 'cyan',
        ],
    ]" :insights="$kamInsights" />