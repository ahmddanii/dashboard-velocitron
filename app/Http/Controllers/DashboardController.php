<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Models\TransactionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private string $api;

    public function __construct()
    {
        $this->api = config('services.flask.url');
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        try {
            if ($user->hasRole('head-analytics')) {
                return $this->dashboardAnalytics();
            } elseif ($user->hasRole('financial-controller')) {
                return $this->dashboardFinance();
            } elseif ($user->hasRole('logistics-officer')) {
                return $this->dashboardLogistics();
            } elseif ($user->hasRole('procurement-director')) {
                return $this->dashboardProcurement();
            } elseif ($user->hasRole('key-account-manager')) {
                return $this->dashboardKAM();
            }

            abort(403);
        } catch (\Exception $e) {
            return view('dashboard.index', ['apiError' => true]);
        }
    }

    // ── Head of Data Analytics ────────────────────────────────
    private function dashboardAnalytics()
    {
        $fetch = fn($endpoint) => Http::timeout(3)->get("{$this->api}/{$endpoint}")->json() ?? [];

        $summary = $fetch('summary');
        $monthly = $fetch('monthly-trend');
        $yearly = $fetch('yearly-trend');
        $category = $fetch('profit-by-category');
        $region = $fetch('sales-by-region');
        $segment = $fetch('sales-by-segment');
        $products = $fetch('top-products');
        /*
|--------------------------------------------------------------------------
| Dashboard Filters
|--------------------------------------------------------------------------
*/

        $statusFilter = request('status');
        $periodFilter = request('period');
        $query = TransactionRequest::query();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($periodFilter) {
            if ($periodFilter === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($periodFilter === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            } elseif ($periodFilter === 'year') {
                $query->whereYear('created_at', now()->year);
            } else {
                $query->where('created_at', '>=', now()->subDays((int) $periodFilter));
            }
        }

        $totalPredictions = TransactionRequest::count();
        $profitablePredictions = TransactionRequest::where('prediction', 1)->count();
        $riskyPredictions = TransactionRequest::where('prediction', 0)->count();
        $avgConfidence = round(TransactionRequest::avg('confidence') ?? 0, 1);

        $approvedCount = TransactionRequest::where('status', 'approved')->count();
        $predictionAccuracy = $totalPredictions > 0 ? round(($approvedCount / $totalPredictions) * 100, 1) : 0;

        /*
|--------------------------------------------------------------------------
| DSS Monitoring
|--------------------------------------------------------------------------
*/

        $totalPredictions =
            (clone $query)->count();

        $profitablePredictions =

            (clone $query)
            ->where(
                'prediction',
                'Profitable'
            )
            ->count();

        $riskyPredictions =

            (clone $query)
            ->where(
                'prediction',
                'Loss'
            )
            ->count();

        $avgConfidence = round(

            (clone $query)

                ->whereNotNull(
                    'confidence'
                )

                ->avg('confidence'),

            1
        );

        $predictionAccuracy =

            $totalPredictions > 0

            ? round(
                ($profitablePredictions / $totalPredictions) * 100,
                1
            )

            : 0;

        /*
|--------------------------------------------------------------------------
| DSS Trend
|--------------------------------------------------------------------------
*/

        $dssTrend =

            (clone $query)
            ->selectRaw('
        DATE(created_at) as date,
        SUM(CASE WHEN prediction = "Profitable" OR prediction = "1" THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN prediction = "Loss" OR prediction = "0" THEN 1 ELSE 0 END) as rejected
    ')
            ->groupBy('date')
            ->orderBy('date')
            ->take(10)
            ->get();

        $totalTransactions = TransactionRequest::count();
        $approvedCount = TransactionRequest::where('status', 'approved')->count();
        $rejectedCount = TransactionRequest::where('status', 'rejected')->count();
        $approvalRate = $totalTransactions > 0 ? round(($approvedCount / $totalTransactions) * 100, 1) : 0;
        $rejectionRate = $totalTransactions > 0 ? round(($rejectedCount / $totalTransactions) * 100, 1) : 0;

        // Risky metrics ignore status filter but respect period filter
        $riskyQuery = TransactionRequest::query();
        if ($periodFilter) {
            if ($periodFilter === '7days') {
                $riskyQuery->where('created_at', '>=', now()->subDays(7));
            } elseif ($periodFilter === '30days') {
                $riskyQuery->where('created_at', '>=', now()->subDays(30));
            } elseif ($periodFilter === 'year') {
                $riskyQuery->whereYear('created_at', now()->year);
            }
        }

        $riskyCategory = (clone $riskyQuery)->where('status', 'rejected')->selectRaw('category, COUNT(*) as total')->groupBy('category')->orderByDesc('total')->first();
        $riskyShipMode = (clone $riskyQuery)->where('status', 'rejected')->selectRaw('ship_mode, COUNT(*) as total')->groupBy('ship_mode')->orderByDesc('total')->first();

        return view('dashboard.index', [

            'role' => 'head-analytics',

            'summary' => $summary,
            'monthly' => $monthly,
            'yearly' => $yearly,
            'category' => $category,
            'region' => $region,
            'segment' => $segment,
            'products' => $products,

            'dashboardData' => [
                'role' => 'head-analytics',
                'monthly' => $monthly,
                'yearly' => $yearly,
                'category' => $category,
                'region' => $region,
                'segment' => $segment,
                'dssTrend' => $dssTrend,
            ],

            'analyticsMonitoring' => [
                'prediction_volume' => $totalPredictions,
                'profitable_predictions' => $profitablePredictions,
                'risky_predictions' => $riskyPredictions,
                'avg_confidence' => $avgConfidence,
                'prediction_accuracy' => $predictionAccuracy,
                'health_status' => $avgConfidence >= 75 ? 'Stable' : 'Monitoring Required',
                'estimated_accuracy' => $predictionAccuracy,
            ],

            'executiveInsights' => [
                "Total prediction processed DSS mencapai {$totalPredictions}.",
                "Prediction profitable mencapai {$profitablePredictions}.",
                "Prediction risky/loss mencapai {$riskyPredictions}.",
                "Average confidence DSS berada di angka {$avgConfidence}%.",
            ],

            'dssAnalytics' => [
                'total_predictions' => $totalTransactions,
                'profitable_predictions' => $profitablePredictions,
                'risky_predictions' => $riskyPredictions,
                'avg_confidence' => $avgConfidence,
                'approval_rate' => $approvalRate,
                'rejection_rate' => $rejectionRate,
                'risky_category' => $riskyCategory?->category ?? '-',
                'risky_ship_mode' => $riskyShipMode?->ship_mode ?? '-',
            ],
            'intelligenceFeed' => $this->getIntelligenceFeed(
                'head-analytics'
            ),
        ]);
    }

    // ── Financial Controller ──────────────────────────────────
    private function dashboardFinance()
    {
        $fetch = fn($endpoint) => Http::timeout(3)->get("{$this->api}/{$endpoint}")->json() ?? [];

        $summary = $fetch('summary');
        $region = $fetch('sales-by-region');
        $category = $fetch('profit-by-category');
        $yearly = $fetch('yearly-trend');

        $statusFilter = request('status');
        $periodFilter = request('period');
        $query = TransactionRequest::query();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($periodFilter) {
            if ($periodFilter === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($periodFilter === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            } elseif ($periodFilter === 'year') {
                $query->whereYear('created_at', now()->year);
            }
        }

        $totalTransactions = TransactionRequest::count();
        $approvedCount = TransactionRequest::where('status', 'approved')->count();
        $rejectedCount = TransactionRequest::where('status', 'rejected')->count();
        $approvalRate = $totalTransactions > 0 ? round(($approvedCount / $totalTransactions) * 100, 1) : 0;
        $rejectionRate = $totalTransactions > 0 ? round(($rejectedCount / $totalTransactions) * 100, 1) : 0;
        $avgConfidence = round(TransactionRequest::whereNotNull('confidence')->avg('confidence') ?? 0, 1);

        // Risky metrics ignore status filter but respect period filter
        $riskyQuery = TransactionRequest::query();
        if ($periodFilter) {
            if ($periodFilter === '7days') {
                $riskyQuery->where('created_at', '>=', now()->subDays(7));
            } elseif ($periodFilter === '30days') {
                $riskyQuery->where('created_at', '>=', now()->subDays(30));
            } elseif ($periodFilter === 'year') {
                $riskyQuery->whereYear('created_at', now()->year);
            }
        }

        $riskyCategory = (clone $riskyQuery)->where('status', 'rejected')->selectRaw('category, COUNT(*) as total')->groupBy('category')->orderByDesc('total')->first();
        $riskyShipMode = (clone $riskyQuery)->where('status', 'rejected')->selectRaw('ship_mode, COUNT(*) as total')->groupBy('ship_mode')->orderByDesc('total')->first();

        $dssTrend = (clone $query)->selectRaw('DATE(created_at) as date, SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved, SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')->groupBy('date')->orderBy('date')->take(10)->get();

        return view('dashboard.index', compact('summary', 'region', 'category', 'yearly') + [
            'role' => 'financial-controller',
            'monthly' => [],
            'segment' => [],
            'products' => [],
            'dashboardData' => [
                'role' => 'financial-controller',
                'monthly' => [],
                'yearly' => $yearly,
                'category' => $category,
                'region' => $region,
                'segment' => [],
                'dssTrend' => $dssTrend,
            ],
            'dssAnalytics' => [
                'total_predictions' => TransactionRequest::count(),
                'profitable_predictions' => TransactionRequest::where('status', 'approved')->count(),
                'risky_predictions' => TransactionRequest::where('status', 'rejected')->count(),
                'avg_confidence' => round(TransactionRequest::whereNotNull('confidence')->avg('confidence') ?? 0, 1),
                'approval_rate' => $approvalRate,
                'rejection_rate' => $rejectionRate,
                'risky_category' => $riskyCategory?->category ?? '-',
                'risky_ship_mode' => $riskyShipMode?->ship_mode ?? '-',
            ],
            'executiveInsights' => [
                "Approval rate mencapai {$approvalRate}% dari seluruh transaksi DSS.",
                "Rata-rata confidence DSS berada di angka {$avgConfidence}% untuk seluruh approval prediction.",
                'Kategori paling berisiko saat ini adalah ' . ($riskyCategory?->category ?? '-') . '.',
                'Ship mode paling sering mengalami reject adalah ' . ($riskyShipMode?->ship_mode ?? '-') . '.',
            ],
            'intelligenceFeed' => $this->getIntelligenceFeed('financial-controller'),
        ]);
    }

    // ── Chief Logistics Officer ───────────────────────────────
    private function dashboardLogistics()
    {
        $apiWarning = false;
        try {
            $summary = Http::timeout(15)->get("{$this->api}/summary")->json() ?? [];
            $region = Http::timeout(15)->get("{$this->api}/sales-by-region")->json() ?? [];
            $yearly = Http::timeout(15)->get("{$this->api}/yearly-trend")->json() ?? [];
        } catch (\Exception $e) {
            $summary = [];
            $region = [];
            $yearly = [];
            // Log error jika perlu atau set flag peringatan
            $apiWarning = true;
        }

        $query = TransactionRequest::query();

        $statusFilter = request('status');
        $periodFilter = request('period');

        $dssTrend = (clone $query)
            ->selectRaw('DATE(created_at) as date, SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved, SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            ->groupBy('date')
            ->orderBy('date')
            ->take(10)
            ->get();

        if ($statusFilter) {

            $query->where(
                'status',
                $statusFilter
            );
        }

        if ($periodFilter === '7days') {

            $query->where(
                'created_at',
                '>=',
                now()->subDays(7)
            );
        } elseif ($periodFilter === '30days') {

            $query->where(
                'created_at',
                '>=',
                now()->subDays(30)
            );
        } elseif ($periodFilter === 'year') {

            $query->whereYear(
                'created_at',
                now()->year
            );
        }

        /*
|--------------------------------------------------------------------------
| Logistics Analytics
|--------------------------------------------------------------------------
*/

        $totalShipment =

            (clone $query)
            ->where(
                'request_type',
                'shipment'
            )
            ->count();

        $approvedShipment =

            (clone $query)
            ->where(
                'request_type',
                'shipment'
            )
            ->where(
                'status',
                'approved'
            )
            ->count();

        $rejectedShipment =

            (clone $query)
            ->where(
                'request_type',
                'shipment'
            )
            ->where(
                'status',
                'rejected'
            )
            ->count();

        $avgShipmentConfidence = round(

            (clone $query)

                ->where(
                    'request_type',
                    'shipment'
                )

                ->whereNotNull(
                    'confidence'
                )

                ->avg('confidence'),

            1
        );

        $mostRiskyShipMode =

            (clone $query)
            ->where(
                'request_type',
                'shipment'
            )
            ->where(
                'status',
                'rejected'
            )
            ->selectRaw('ship_mode, COUNT(*) as total')
            ->groupBy('ship_mode')
            ->orderByDesc('total')
            ->first();

        return view('dashboard.index', compact(
            'summary',
            'region',
            'yearly',
            'dssTrend',
            'apiWarning'
        ) + [

            'role' => 'logistics-officer',
            'apiWarning' => $apiWarning ?? false,

            'monthly' => [],
            'yearly' => $yearly,
            'category' => [],
            'segment' => [],
            'products' => [],

            'dashboardData' => [

                'role' => 'logistics-officer',

                'monthly' => [],
                'yearly' => $yearly,

                'category' => [],
                'region' => $region,

                'segment' => [],
                'dssTrend' => $dssTrend,
            ],

            'logisticsAnalytics' => [
                'total_shipment' => $totalShipment,
                'approved_shipment' => $approvedShipment,
                'rejected_shipment' => $rejectedShipment,
                'approval_rate' => $totalShipment > 0
                    ? round(($approvedShipment / $totalShipment) * 100, 1)
                    : 0,
                'avg_confidence' => $avgShipmentConfidence,
                'risky_ship_mode' => $mostRiskyShipMode?->ship_mode ?? '-', // ← ganti key ini
            ],

            'logisticsInsights' => [ // ← key ini yang tadi hilang
                "Total shipment request tercatat sebanyak {$totalShipment}.",
                "Approved shipment mencapai {$approvedShipment} request.",
                "Rejected shipment mencapai {$rejectedShipment} request.",
                'Ship mode paling risky saat ini adalah ' . ($mostRiskyShipMode?->ship_mode ?? '-') . '.',
            ],

            'intelligenceFeed' => $this->getIntelligenceFeed(
                'logistics-officer'
            ),

        ]);
    }

    // ── Director of Strategic Procurement ────────────────────
    private function dashboardProcurement()
    {
        $summary = Http::timeout(5)
            ->get("{$this->api}/summary")
            ->json() ?? [];

        $category = Http::timeout(5)
            ->get("{$this->api}/profit-by-category")
            ->json() ?? [];

        $products = Http::timeout(5)->get("{$this->api}/top-products")->json() ?? [];
        /*
|--------------------------------------------------------------------------
| Dashboard Filters
|--------------------------------------------------------------------------
*/

        $statusFilter = request('status');

        $periodFilter = request('period');

        $query = TransactionRequest::query();

        if ($statusFilter) {

            $query->where(
                'status',
                $statusFilter
            );
        }

        if ($periodFilter === '7days') {

            $query->where(
                'created_at',
                '>=',
                now()->subDays(7)
            );
        } elseif ($periodFilter === '30days') {

            $query->where(
                'created_at',
                '>=',
                now()->subDays(30)
            );
        } elseif ($periodFilter === 'year') {

            $query->whereYear(
                'created_at',
                now()->year
            );
        }

        /*
|--------------------------------------------------------------------------
| Procurement Analytics
|--------------------------------------------------------------------------
*/

        $totalProcurement =

            (clone $query)
            ->where(
                'request_type',
                'procurement'
            )
            ->count();

        $approvedProcurement =

            (clone $query)
            ->where(
                'request_type',
                'procurement'
            )
            ->where(
                'status',
                'approved'
            )
            ->count();

        $rejectedProcurement =

            (clone $query)
            ->where(
                'request_type',
                'procurement'
            )
            ->where(
                'status',
                'rejected'
            )
            ->count();

        $avgProcurementConfidence = round(

            (clone $query)

                ->where(
                    'request_type',
                    'procurement'
                )

                ->whereNotNull(
                    'confidence'
                )

                ->avg('confidence'),

            1
        );

        $mostRejectedCategory =

            (clone $query)
            ->where(
                'request_type',
                'procurement'
            )
            ->where(
                'status',
                'rejected'
            )
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        return view('dashboard.index', compact(
            'summary',
            'category',
            'products'
        ) + [

            'role' => 'procurement-director',

            'monthly' => [],
            'yearly' => [],
            'region' => [],
            'segment' => [],
            'products' => [],

            'dashboardData' => [

                'role' => 'procurement-director',

                'monthly' => [],
                'yearly' => [],

                'category' => $category,
                'region' => [],

                'segment' => [],
            ],

            'procurementAnalytics' => [
                'total_procurement' => $totalProcurement,
                'approved_procurement' => $approvedProcurement,
                'rejected_procurement' => $rejectedProcurement,
                'approval_rate' => $totalProcurement > 0
                    ? round(($approvedProcurement / $totalProcurement) * 100, 1)
                    : 0,
                'avg_confidence' => $avgProcurementConfidence,
                'risky_category' => $mostRejectedCategory?->category ?? '-', // ← ganti key ini
            ],

            'procurementInsights' => [
                "Total procurement request tercatat sebanyak {$totalProcurement}.",
                "Approved procurement mencapai {$approvedProcurement} request.",
                "Rejected procurement mencapai {$rejectedProcurement} request.",
                'Kategori procurement paling sering ditolak adalah ' . ($mostRejectedCategory?->category ?? '-') . '.',
            ],

            'intelligenceFeed' => $this->getIntelligenceFeed(
                'procurement-director'
            ),

        ]);
    }

    // ── Key Account Manager ───────────────────────────────────
    private function dashboardKAM()
    {
        $summary = Http::timeout(5)
            ->get("{$this->api}/summary")
            ->json() ?? [];

        $segment = Http::timeout(5)
            ->get("{$this->api}/sales-by-segment")
            ->json() ?? [];

        $region = Http::timeout(5)
            ->get("{$this->api}/sales-by-region")
            ->json() ?? [];

        /*
|--------------------------------------------------------------------------
| Filter Segment
|--------------------------------------------------------------------------
*/

        $segment = array_values(array_filter(
            $segment,
            fn($s) => in_array(
                $s['segment'],
                ['Corporate', 'Home Office']
            )
        ));

        /*
|--------------------------------------------------------------------------
| Dashboard Filters
|--------------------------------------------------------------------------
*/

        $statusFilter = request('status');

        $periodFilter = request('period');

        $query = TransactionRequest::query();

        if ($statusFilter) {

            $query->where(
                'status',
                $statusFilter
            );
        }

        if ($periodFilter === '7days') {

            $query->where(
                'created_at',
                '>=',
                now()->subDays(7)
            );
        } elseif ($periodFilter === '30days') {

            $query->where(
                'created_at',
                '>=',
                now()->subDays(30)
            );
        } elseif ($periodFilter === 'year') {

            $query->whereYear(
                'created_at',
                now()->year
            );
        }

        /*
|--------------------------------------------------------------------------
| KAM Analytics
|--------------------------------------------------------------------------
*/

        $totalContracts =

            (clone $query)
            ->where(
                'request_type',
                'contract'
            )
            ->count();

        $approvedContracts =

            (clone $query)
            ->where(
                'request_type',
                'contract'
            )
            ->where(
                'status',
                'approved'
            )
            ->count();

        $rejectedContracts =

            (clone $query)
            ->where(
                'request_type',
                'contract'
            )
            ->where(
                'status',
                'rejected'
            )
            ->count();

        $avgContractConfidence = round(

            (clone $query)

                ->where(
                    'request_type',
                    'contract'
                )

                ->whereNotNull(
                    'confidence'
                )

                ->avg('confidence'),

            1
        );

        $topContractRegion =

            (clone $query)
            ->where(
                'request_type',
                'contract'
            )
            ->selectRaw('region, COUNT(*) as total')
            ->groupBy('region')
            ->orderByDesc('total')
            ->first();

        /*
|--------------------------------------------------------------------------
| DSS Intelligence Feed
|--------------------------------------------------------------------------
*/

        $strategies = Strategy::latest()

            ->where(
                'target_role',
                'key-account-manager'
            )

            ->take(5)

            ->get();

        return view('dashboard.index', compact(
            'summary',
            'segment',
            'region',
            'strategies'
        ) + [

            'role' => 'key-account-manager',

            'monthly' => [],
            'yearly' => [],
            'category' => [],
            'products' => [],

            'dashboardData' => [

                'role' => 'key-account-manager',

                'monthly' => [],
                'yearly' => [],

                'category' => [],
                'region' => $region,

                'segment' => $segment,
            ],

            'kamAnalytics' => [

                'total_contracts' => $totalContracts,

                'approved_contracts' => $approvedContracts,

                'rejected_contracts' => $rejectedContracts,

                'approval_rate' => $totalContracts > 0
                    ? round(($approvedContracts / $totalContracts) * 100, 1)
                    : 0,

                'avg_confidence' => $avgContractConfidence,

                'top_segment' => collect($segment)->sortByDesc('total_sales')->first()['segment'] ?? '-',

                'top_region' => $topContractRegion?->region
                    ?? '-',
            ],

            'kamInsights' => [
                "Total contract request tercatat sebanyak {$totalContracts}.",
                "Approved contracts mencapai {$approvedContracts}.",
                "Rejected contracts mencapai {$rejectedContracts}.",
                'Region kontrak tertinggi saat ini adalah ' . ($topContractRegion?->region ?? '-') . '.',
            ],

            'intelligenceFeed' => $this->getIntelligenceFeed(
                'key-account-manager'
            ),

        ]);
    }

    // ── DSS ──────────────────────────────────────────────────
    public function dss()
    {
        return view('dashboard.dss');
    }

    public function predict(Request $request)
    {
        $request->validate([
            'sales' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1|max:14',
            'discount' => 'required|numeric|min:0|max:0.8',
            'shipping_days' => 'required|integer|min:0|max:7',
            'category' => 'required|in:Furniture,Office Supplies,Technology',
            'segment' => 'required|in:Consumer,Corporate,Home Office',
            'region' => 'required|in:East,West,Central,South',
            'ship_mode' => 'required|in:First Class,Second Class,Standard Class,Same Day',
        ]);

        try {
            $response = Http::timeout(5)->post("{$this->api}/predict-profit", [
                'sales' => (float) $request->sales,
                'quantity' => (int) $request->quantity,
                'discount' => (float) $request->discount,
                'shipping_days' => (int) $request->shipping_days,
                'category' => $request->category,
                'segment' => $request->segment,
                'region' => $request->region,
                'ship_mode' => $request->ship_mode,
            ]);

            $result = $response->json();
            $prediction = $result['prediction'] ?? 'Unknown';
            $confidence = $result['confidence'] ?? 0;

            if ($prediction === 'Loss') {
                Strategy::create([
                    'target_role' => 'logistics-officer',
                    'title' => 'Optimasi Pengiriman',
                    'recommendation' => 'Gunakan Standard Class untuk menekan biaya distribusi.',
                    'prediction' => $prediction,
                    'confidence' => $confidence,
                ]);

                Strategy::create([
                    'target_role' => 'procurement-director',
                    'title' => 'Batasi Margin Procurement',
                    'recommendation' => 'Kurangi pembelian pada kategori dengan margin rendah.',
                    'prediction' => $prediction,
                    'confidence' => $confidence,
                ]);

                Strategy::create([
                    'target_role' => 'key-account-manager',
                    'title' => 'Batasi Diskon Client',
                    'recommendation' => 'Hindari pemberian diskon tinggi pada kontrak baru.',
                    'prediction' => $prediction,
                    'confidence' => $confidence,
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Flask API tidak dapat dihubungi.']);
        }

        return view('dashboard.dss', [
            'result' => $result,
            'input' => $request->all(),
        ]);
    }

    public function createRequest()
    {
        $role = auth()->user()->roles->first()?->name;
        $requestTypeMap = $this->getRequestConfig($role);
        $requestMeta = $requestTypeMap[$role] ?? null;
        abort_if(! $requestMeta, 403);

        return view('requests.create', compact('requestMeta'));
    }

    public function storeRequest(Request $request)
    {
        $role = auth()->user()->roles->first()?->name;

        $requestTypeMap = [
            'procurement-director' => 'procurement',
            'logistics-officer' => 'shipment',
            'key-account-manager' => 'contract',
        ];

        $configMap = $this->getRequestConfig($role);
        $requestMeta = $configMap[$role] ?? null;

        if ($requestMeta) {
            foreach ($requestMeta['fields'] as $field => $config) {
                if (!$request->has($field) && isset($config['default'])) {
                    $request->merge([$field => $config['default']]);
                }
            }
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'sales' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'discount' => 'required|numeric|min:0|max:0.8',
            'shipping_days' => 'required|integer|min:0|max:7',
            'category' => 'required',
            'segment' => 'required',
            'region' => 'required',
            'ship_mode' => 'required',
        ]);

        TransactionRequest::create([
            'requester_id' => auth()->id(),
            'request_type' => $requestTypeMap[$role] ?? 'unknown',
            'title' => $request->title,
            'description' => $request->description,
            'sales' => $request->sales,
            'quantity' => $request->quantity,
            'discount' => $request->discount,
            'shipping_days' => $request->shipping_days,
            'category' => $request->category,
            'segment' => $request->segment,
            'region' => $request->region,
            'ship_mode' => $request->ship_mode,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Request berhasil diajukan ke Financial Controller.');
    }

    public function editRequest($id)
    {
        $requestItem = TransactionRequest::findOrFail($id);

        // Security check
        abort_if($requestItem->requester_id !== auth()->id(), 403, 'Unauthorized access.');
        abort_if($requestItem->status !== 'pending', 403, 'Hanya request pending yang bisa diedit.');

        $role = auth()->user()->roles->first()?->name;

        $requestTypeMap = $this->getRequestConfig($role);
        $requestMeta = $requestTypeMap[$role] ?? null;
        abort_if(! $requestMeta, 403);

        return view('requests.edit', compact('requestMeta', 'requestItem'));
    }

    public function updateRequest(Request $request, $id)
    {
        $requestItem = TransactionRequest::findOrFail($id);

        // Security check
        abort_if($requestItem->requester_id !== auth()->id(), 403, 'Unauthorized access.');
        abort_if($requestItem->status !== 'pending', 403, 'Hanya request pending yang bisa diupdate.');

        $role = auth()->user()->roles->first()?->name;
        $configMap = $this->getRequestConfig($role);
        $requestMeta = $configMap[$role] ?? null;

        if ($requestMeta) {
            foreach ($requestMeta['fields'] as $field => $config) {
                if (!$request->has($field) && isset($config['default'])) {
                    $request->merge([$field => $config['default']]);
                }
            }
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'sales' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'discount' => 'required|numeric|min:0|max:0.8',
            'shipping_days' => 'required|integer|min:0|max:7',
            'category' => 'required',
            'segment' => 'required',
            'region' => 'required',
            'ship_mode' => 'required',
        ]);

        $requestItem->update([
            'title' => $request->title,
            'description' => $request->description,
            'sales' => $request->sales,
            'quantity' => $request->quantity,
            'discount' => $request->discount,
            'shipping_days' => $request->shipping_days,
            'category' => $request->category,
            'segment' => $request->segment,
            'region' => $request->region,
            'ship_mode' => $request->ship_mode,
        ]);

        return redirect()->route('transactions.history')
            ->with('status', 'Request berhasil diupdate!');
    }

    public function cancelRequest($id)
    {
        $requestItem = TransactionRequest::findOrFail($id);

        // Security check
        abort_if($requestItem->requester_id !== auth()->id(), 403, 'Unauthorized access.');
        abort_if($requestItem->status !== 'pending', 403, 'Hanya request pending yang bisa dibatalkan.');

        $requestItem->delete();

        return redirect()->route('transactions.history')
            ->with('status', 'Request berhasil dibatalkan dan dihapus.');
    }

    private function getRequestConfig(string $role): array
    {
        return [
            'procurement-director' => [
                'type' => 'procurement',
                'title' => 'Procurement Request',
                'description' => 'Edit pengadaan inventory & supplier procurement.',
                'fields' => [
                    'sales' => ['show' => true,  'label' => 'Estimated Cost ($)'],
                    'quantity' => ['show' => true,  'label' => 'Order Quantity'],
                    'discount' => ['show' => false, 'default' => 0.0],
                    'shipping_days' => ['show' => false, 'default' => 4],
                    'category' => ['show' => true,  'label' => 'Product Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment' => ['show' => false, 'default' => 'Consumer'],
                    'region' => ['show' => true,  'label' => 'Supplier Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode' => ['show' => false, 'default' => 'Standard Class'],
                ],
            ],
            'logistics-officer' => [
                'type' => 'shipment',
                'title' => 'Shipment Request',
                'description' => 'Edit distribusi & shipment approval.',
                'fields' => [
                    'sales' => ['show' => true,  'label' => 'Shipment Value ($)'],
                    'quantity' => ['show' => true,  'label' => 'Package Quantity'],
                    'discount' => ['show' => false, 'default' => 0.0],
                    'shipping_days' => ['show' => true,  'label' => 'Estimasi Hari Kirim'],
                    'category' => ['show' => true,  'label' => 'Product Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment' => ['show' => true,  'label' => 'Customer Segment', 'options' => ['Consumer', 'Corporate', 'Home Office']],
                    'region' => ['show' => true,  'label' => 'Destination Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode' => ['show' => true,  'label' => 'Ship Mode', 'options' => ['First Class', 'Second Class', 'Standard Class', 'Same Day']],
                ],
            ],
            'key-account-manager' => [
                'type' => 'contract',
                'title' => 'Contract Request',
                'description' => 'Edit kontrak client & discount approval.',
                'fields' => [
                    'sales' => ['show' => true,  'label' => 'Nilai Kontrak ($)'],
                    'quantity' => ['show' => true,  'label' => 'Jumlah Item'],
                    'discount' => ['show' => true,  'label' => 'Diskon Klien'],
                    'shipping_days' => ['show' => false, 'default' => 4],
                    'category' => ['show' => true,  'label' => 'Product Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment' => ['show' => true,  'label' => 'Client Segment', 'options' => ['Corporate', 'Home Office']],
                    'region' => ['show' => true,  'label' => 'Client Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode' => ['show' => false, 'default' => 'Standard Class'],
                ],
            ],
            'head-analytics' => [
                'type' => 'analytics',
                'title' => 'DSS Analytics Request',
                'description' => 'Detail pengajuan untuk analisis DSS.',
                'fields' => [
                    'sales' => ['show' => true,  'label' => 'Value ($)'],
                    'quantity' => ['show' => true,  'label' => 'Quantity'],
                    'discount' => ['show' => true,  'label' => 'Discount'],
                    'shipping_days' => ['show' => true,  'label' => 'Shipping Days'],
                    'category' => ['show' => true,  'label' => 'Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment' => ['show' => true,  'label' => 'Segment', 'options' => ['Consumer', 'Corporate', 'Home Office']],
                    'region' => ['show' => true,  'label' => 'Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode' => ['show' => true,  'label' => 'Ship Mode', 'options' => ['First Class', 'Second Class', 'Standard Class', 'Same Day']],
                ],
            ],
            'financial-controller' => [
                'type' => 'finance',
                'title' => 'Finance Request',
                'description' => 'Detail pengajuan untuk kontrol finansial.',
                'fields' => [
                    'sales' => ['show' => true,  'label' => 'Total Sales ($)'],
                    'quantity' => ['show' => true,  'label' => 'Quantity'],
                    'discount' => ['show' => true,  'label' => 'Discount'],
                    'shipping_days' => ['show' => true,  'label' => 'Shipping Days'],
                    'category' => ['show' => true,  'label' => 'Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment' => ['show' => true,  'label' => 'Segment', 'options' => ['Consumer', 'Corporate', 'Home Office']],
                    'region' => ['show' => true,  'label' => 'Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode' => ['show' => true,  'label' => 'Ship Mode', 'options' => ['First Class', 'Second Class', 'Standard Class', 'Same Day']],
                ],
            ],
        ];
    }

    public function pendingRequests()
    {
        $requests = TransactionRequest::latest()
            ->where('status', 'pending')
            ->with('requester')
            ->get();

        return view('requests.pending', compact('requests'));
    }

    public function reviewRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);
        $result = null;

        try {
            $response = Http::timeout(10)->post("{$this->api}/predict-profit", [
                'sales' => (float) $requestData->sales,
                'quantity' => (int) $requestData->quantity,
                'discount' => (float) $requestData->discount,
                'shipping_days' => (int) $requestData->shipping_days,
                'category' => $requestData->category,
                'segment' => $requestData->segment,
                'region' => $requestData->region,
                'ship_mode' => $requestData->ship_mode,
            ]);

            $result = $response->json();
        } catch (\Exception $e) {
            \Log::error('DSS Review Error: ' . $e->getMessage());
        }

        // ✅ Update database terpisah dari fetch — pakai prob_profitable (angka) bukan confidence (string)
        if ($result) {
            try {
                $requestData->update([
                    'prediction' => $result['label_id'] ?? null,
                    'confidence' => $result['prob_profitable'] ?? null, // ← angka desimal, bukan string
                ]);
            } catch (\Exception $e) {
                \Log::error('DB Update Error: ' . $e->getMessage());
            }
        }

        return view('requests.review', compact('requestData', 'result'));
    }

    public function apiReviewRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);
        $result = null;

        try {
            $response = Http::timeout(10)->post("{$this->api}/predict-profit", [
                'sales' => (float) $requestData->sales,
                'quantity' => (int) $requestData->quantity,
                'discount' => (float) $requestData->discount,
                'shipping_days' => (int) $requestData->shipping_days,
                'category' => $requestData->category,
                'segment' => $requestData->segment,
                'region' => $requestData->region,
                'ship_mode' => $requestData->ship_mode,
            ]);

            $result = $response->json();
        } catch (\Exception $e) {
            \Log::error('DSS API Review Error: ' . $e->getMessage());
        }

        if ($result) {
            $requestData->update([
                'prediction' => $result['label_id'] ?? null,
                'confidence' => $result['prob_profitable'] ?? null,
            ]);
        }

        return response()->json([
            'request' => $requestData->load('requester'),
            'result' => $result,
        ]);
    }

    public function approveRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);

        try {
            $response = Http::timeout(10)->post("{$this->api}/predict-profit", [
                'sales' => (float) $requestData->sales,
                'quantity' => (int) $requestData->quantity,
                'discount' => (float) $requestData->discount,
                'shipping_days' => (int) $requestData->shipping_days,
                'category' => $requestData->category,
                'segment' => $requestData->segment,
                'region' => $requestData->region,
                'ship_mode' => $requestData->ship_mode,
            ]);

            $result = $response->json();
        } catch (\Exception $e) {
            $result = null;
        }

        $requestData->update([
            'status' => 'approved',
            'prediction' => $result['label_id'] ?? null,
            'confidence' => $result['prob_profitable'] ?? null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('requests.pending')
            ->with('success', 'Request berhasil di-approve.');
    }

    public function rejectRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);

        try {
            $response = Http::timeout(10)->post("{$this->api}/predict-profit", [
                'sales' => (float) $requestData->sales,
                'quantity' => (int) $requestData->quantity,
                'discount' => (float) $requestData->discount,
                'shipping_days' => (int) $requestData->shipping_days,
                'category' => $requestData->category,
                'segment' => $requestData->segment,
                'region' => $requestData->region,
                'ship_mode' => $requestData->ship_mode,
            ]);

            $result = $response->json();
        } catch (\Exception $e) {
            $result = null;
        }

        $requestData->update([
            'status' => 'rejected',
            'prediction' => $result['label_id'] ?? null,
            'confidence' => $result['prob_profitable'] ?? null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('requests.pending')
            ->with('error', 'Request telah ditolak.');
    }

    public function transactionHistory()
    {
        $role = auth()->user()->roles->first()?->name;
        $tab = request('tab', 'dss');

        // ── Transaction Queries ──────────────────────────────────
        $query = TransactionRequest::latest()
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->where('is_imported', false)
            ->with(['requester', 'approver']);

        // Separate query for imported data
        $importedQuery = TransactionRequest::latest()
            ->where('is_imported', true)
            ->with(['requester']);

        if ($role === 'procurement-director') {
            $query->where('request_type', 'procurement');
            $importedQuery->where('request_type', 'procurement');
        } elseif ($role === 'logistics-officer') {
            $query->where('request_type', 'shipment');
            $importedQuery->where('request_type', 'shipment');
        } elseif ($role === 'key-account-manager') {
            $query->where('request_type', 'contract');
            $importedQuery->where('request_type', 'contract');
        }

        // Get counts for tab badges in one single query for speed
        $stats = TransactionRequest::selectRaw("
            COUNT(CASE WHEN is_imported = 0 THEN 1 END) as dss_total,
            COUNT(CASE WHEN is_imported = 1 THEN 1 END) as imported_total,
            COUNT(CASE WHEN is_imported = 1 AND prediction IS NULL THEN 1 END) as sync_remaining
        ")->first();

        $dssTotal = $stats->dss_total ?? 0;
        $importedTotal = $stats->imported_total ?? 0;
        $syncRemaining = $stats->sync_remaining ?? 0;

        // Paginate based on active tab
        if ($tab === 'imported') {
            $transactions = $importedQuery->paginate(20)->appends(['tab' => 'imported']);
        } else {
            $transactions = $query->paginate(15)->appends(request()->except('page'));
        }

        // Ambil config field untuk modal edit di view
        $requestTypeMap = $this->getRequestConfig($role);
        $requestMeta = $requestTypeMap[$role] ?? [
            'type' => 'unknown',
            'title' => 'Request',
            'description' => 'Detail pengajuan.',
            'fields' => [
                'sales' => ['show' => true, 'label' => 'Value'],
                'quantity' => ['show' => true, 'label' => 'Quantity'],
                'discount' => ['show' => false, 'label' => 'Discount', 'default' => 0],
                'shipping_days' => ['show' => false, 'label' => 'Days', 'default' => 0],
                'category' => ['show' => true, 'label' => 'Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                'segment' => ['show' => true, 'label' => 'Segment', 'options' => ['Consumer', 'Corporate', 'Home Office']],
                'region' => ['show' => true, 'label' => 'Region', 'options' => ['East', 'West', 'Central', 'South']],
                'ship_mode' => ['show' => false, 'label' => 'Ship Mode', 'default' => 'Standard Class'],
            ],
        ];

        // ── Historical Orders dari Flask API ──────────────────────
        $historicalPage = (int) request('historical_page', 1);
        $category = request('category', '');
        $region = request('region', '');
        $segment = request('segment', '');

        try {
            $response = Http::timeout(10)->get("{$this->api}/orders", [
                'page' => $historicalPage,
                'per_page' => 15,
                'category' => $category,
                'region' => $region,
                'segment' => $segment,
            ])->json();

            $historicalOrders = $response['data'] ?? [];
            $historicalTotal = $response['total'] ?? 0;
            $historicalLastPage = $response['last_page'] ?? 1;
        } catch (\Exception $e) {
            $historicalOrders = [];
            $historicalTotal = 0;
            $historicalLastPage = 1;
        }

        return view('transactions.history', compact(
            'transactions',
            'dssTotal',
            'importedTotal',
            'syncRemaining',
            'historicalOrders',
            'historicalTotal',
            'historicalLastPage',
            'historicalPage',
            'role',
            'requestMeta'
        ));
    }

    public function previewExport()
    {
        $transactions = TransactionRequest::latest()
            ->with(['requester', 'approver'])
            ->take(10) // Hanya ambil 10 untuk preview
            ->get();

        return response()->json([
            'count' => TransactionRequest::count(),
            'data' => $transactions,
        ]);
    }

    public function exportTransactions()
    {
        $transactions = TransactionRequest::latest()
            ->with(['requester', 'approver'])
            ->get();

        $filename = 'transaction-report-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Title',
                'Request Type',
                'Requester',
                'Sales',
                'Quantity',
                'Prediction',
                'Confidence',
                'Status',
                'Approved By',
                'Created At',
            ]);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->title,
                    $t->request_type,
                    $t->requester?->name,
                    $t->sales,
                    $t->quantity,
                    $t->prediction,
                    $t->confidence,
                    strtoupper($t->status),
                    $t->approver?->name,
                    $t->created_at,
                ]);
            }

            fclose($file);
        };

        session()->flash('success', 'Export transaction CSV berhasil diunduh.');

        return response()->stream($callback, 200, $headers);
    }

    public function previewAnalyticsExport()
    {
        $transactions = TransactionRequest::all();
        $total = $transactions->count();
        $approved = $transactions->where('status', 'approved')->count();
        $rejected = $transactions->where('status', 'rejected')->count();
        $avgConf = round($transactions->avg('confidence') ?? 0, 1);

        return response()->json([
            'metrics' => [
                'total' => $total,
                'approved' => $approved,
                'rejected' => $rejected,
                'avg_confidence' => $avgConf . '%',
            ],
            'recent' => TransactionRequest::latest()->take(5)->get(),
        ]);
    }

    public function exportAnalyticsReport()
    {
        $transactions = TransactionRequest::all();
        $totalPredictions = $transactions->count();
        $approved = $transactions->where('status', 'approved')->count();
        $rejected = $transactions->where('status', 'rejected')->count();
        $avgConfidence = round($transactions->avg('confidence'), 1);

        $filename = 'dss-monitoring-report-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($totalPredictions, $approved, $rejected, $avgConfidence) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Prediction Volume',     $totalPredictions]);
            fputcsv($file, ['Approved Transactions', $approved]);
            fputcsv($file, ['Rejected Transactions', $rejected]);
            fputcsv($file, ['Average Confidence',    $avgConfidence . '%']);
            fclose($file);
        };

        session()->flash('success', 'DSS Monitoring Report berhasil diexport.');

        return response()->stream($callback, 200, $headers);
    }

    public function downloadImportTemplate()
    {
        $filename = 'transaction-import-template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Title',
                'Request Type',
                'Sales',
                'Quantity',
                'Discount',
                'Shipping Days',
                'Category',
                'Segment',
                'Region',
                'Ship Mode',
                'Description',
            ]);
            // Sample row
            fputcsv($file, [
                'Sample Office Supplies Order',
                'procurement',
                '250.50',
                '5',
                '0.1',
                '4',
                'Office Supplies',
                'Consumer',
                'Central',
                'Standard Class',
                'Kebutuhan alat tulis kantor cabang pusat',
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importTransactions(Request $request)
    {
        // Tingkatkan limit untuk file gede & ribuan request API
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $validator = \Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:20480',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal upload: ' . $validator->errors()->first());
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        try {
            // Remove BOM (Byte Order Mark) if present
            $rawFirstLine = fgets($handle);
            $rawFirstLine = ltrim($rawFirstLine, "\xEF\xBB\xBF");
            rewind($handle);
            fgets($handle); // skip BOM-cleaned line so we can re-read properly
            rewind($handle);

            // Auto-detect delimiter: comma or semicolon
            $firstLine = fgets($handle);
            $firstLine = ltrim($firstLine, "\xEF\xBB\xBF");
            $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
            rewind($handle);

            $headers = fgetcsv($handle, 0, $delimiter);
            if (!$headers) {
                fclose($handle);
                return redirect()->back()->with('error', 'Format file CSV tidak valid atau kosong.');
            }

            // Normalize headers for mapping
            $normalizedHeaders = array_map(function ($h) {
                return strtolower(trim(str_replace([' ', '_', '-', '.'], '', $h)));
            }, $headers);
            $headerMap = array_flip($normalizedHeaders);

            // Define aliases for required fields
            $aliases = [
                'title'         => ['title', 'productname', 'ordername', 'product', 'item', 'nama', 'namaproduk', 'name', 'productid', 'orderid', 'ordername'],
                'sales'         => ['sales', 'amount', 'price', 'value', 'nilai', 'hargajual', 'total', 'revenue', 'totalsales', 'saletotal'],
                'quantity'      => ['quantity', 'qty', 'count', 'jumlah', 'unit', 'units', 'orderquantity'],
                'discount'      => ['discount', 'diskon', 'potongan', 'cut', 'disc', 'discountrate'],
                'shipping_days' => ['shippingdays', 'daystoship', 'leadtime', 'harikirim', 'estimasi', 'shipdays', 'daytoship', 'daysshipping'],
                'category'      => ['category', 'kategori', 'productcategory', 'tipeproduk', 'cat', 'productcat', 'producttype'],
                'segment'       => ['segment', 'segmen', 'marketsegment', 'pasar', 'customersegment', 'marketseg'],
                'region'        => ['region', 'wilayah', 'area', 'lokasi', 'daerah', 'salesregion', 'territory'],
                'ship_mode'     => ['shipmode', 'shippingmethod', 'modekirim', 'kurir', 'pengiriman', 'shippingmode', 'deliverymode'],
                'description'   => ['description', 'deskripsi', 'notes', 'catatan', 'keterangan', 'note', 'desc'],
                'request_type'  => ['requesttype', 'tiperequest', 'jenis', 'type', 'reqtype'],
            ];

            // Helper to find column index by alias
            $findIdx = function ($key) use ($aliases, $headerMap) {
                foreach ($aliases[$key] as $alias) {
                    if (isset($headerMap[$alias])) return $headerMap[$alias];
                }
                return -1;
            };

            // Map all critical indices
            $indices = [];
            $missingColumns = [];
            foreach ($aliases as $key => $vals) {
                $idx = $findIdx($key);
                $indices[$key] = $idx;
                if ($idx === -1 && in_array($key, ['title', 'sales'])) {
                    $missingColumns[] = ucwords($key);
                }
            }

            if (!empty($missingColumns)) {
                fclose($handle);
                $cols = implode(' atau ', $missingColumns);
                return redirect()->back()->with('error', "Gagal: Kolom [{$cols}] tidak ditemukan. Pastikan header CSV sudah benar.");
            }

            \DB::connection()->disableQueryLog();
            \DB::beginTransaction();

            $rowCount = 0;
            $errorCount = 0;
            $now = now();
            $batch = [];
            $batchSize = 1000;

            $normalizeString = function ($val, $default) {
                if (!$val) return $default;
                $val = strtolower(trim($val));
                if ($val === 'office supplies') return 'Office Supplies';
                if (in_array($val, ['standard class', 'standard'])) return 'Standard Class';
                if (in_array($val, ['second class', 'second'])) return 'Second Class';
                if (in_array($val, ['first class', 'first'])) return 'First Class';
                if (in_array($val, ['same day', 'sameday'])) return 'Same Day';
                return ucwords($val);
            };

            $getVal = function ($key, $data, $indices) {
                $idx = $indices[$key];
                return ($idx !== -1 && isset($data[$idx])) ? trim($data[$idx]) : null;
            };

            $reqId = auth()->id();
            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $title = ($indices['title'] !== -1 && isset($data[$indices['title']])) ? trim($data[$indices['title']]) : null;
                if (!$title) continue;

                $getRaw = fn($k) => ($indices[$k] !== -1 && isset($data[$indices[$k]])) ? trim($data[$indices[$k]]) : null;

                $sales = (float) str_replace([',', '$'], '', $getRaw('sales') ?? 0);
                $disc = (float) ($getRaw('discount') ?? 0);

                $batch[] = [
                    'requester_id' => $reqId,
                    'title' => $title,
                    'request_type' => $getRaw('request_type') ?? 'procurement',
                    'sales' => $sales,
                    'quantity' => (int) ($getRaw('quantity') ?? 1),
                    'discount' => ($disc > 1) ? ($disc / 100) : $disc,
                    'shipping_days' => (int) ($getRaw('shipping_days') ?? 4),
                    'category' => $normalizeString($getRaw('category'), 'Office Supplies'),
                    'segment' => $normalizeString($getRaw('segment'), 'Consumer'),
                    'region' => $normalizeString($getRaw('region'), 'Central'),
                    'ship_mode' => $normalizeString($getRaw('ship_mode'), 'Standard Class'),
                    'description' => $getRaw('description'),
                    'status' => 'approved',
                    'is_imported' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $rowCount++;

                if (count($batch) >= $batchSize) {
                    \DB::table('transaction_requests')->insert($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                \DB::table('transaction_requests')->insert($batch);
            }

            \DB::commit();

            fclose($handle);

            if ($rowCount === 0) {
                return redirect()->back()->with('error', 'Tidak ada data yang berhasil diimport. Periksa format file Anda.');
            }

            $message = "Berhasil mengimport {$rowCount} data transaksi.";
            if ($errorCount > 0) $message .= " Terjadi kesalahan pada {$errorCount} baris.";

            return redirect()->route('transactions.history', ['tab' => 'imported'])
                ->with($errorCount > 0 ? 'warning' : 'success', $message);
        } catch (\Exception $e) {
            \DB::rollBack();
            if (isset($handle) && is_resource($handle)) fclose($handle);
            \Log::error('Global Import Error: ' . $e->getMessage());

            $msg = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            if (str_contains($e->getMessage(), 'upload_max_filesize')) {
                $msg = 'Gagal: Ukuran file terlalu besar untuk diproses server.';
            }

            return redirect()->back()->with('error', $msg);
        }
    }

    private function getIntelligenceFeed($role)
    {
        return Strategy::where('target_role', $role)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($s) {
                return [
                    'title'      => $s->title,
                    'message'    => $s->recommendation,
                    'status'     => $s->prediction === 'Loss' ? 'rejected' : 'approved',
                    'created_at' => $s->created_at,
                ];
            });
    }
    public function clearImportedTransactions()
    {
        $count = TransactionRequest::where('is_imported', true)->count();
        TransactionRequest::where('is_imported', true)->delete();

        return redirect()->route('transactions.history', ['tab' => 'imported'])
            ->with('success', "Berhasil menghapus {$count} data transaksi yang diimport.");
    }

    public function ajaxPredictImported()
    {
        $batchSize = 500; // Tingkatkan ke 500 untuk 50k+ row!

        $transactions = TransactionRequest::where('is_imported', true)
            ->whereNull('prediction')
            ->limit($batchSize)
            ->get();

        if ($transactions->isEmpty()) {
            return response()->json([
                'finished' => true,
                'message' => 'Semua data sudah sinkron.'
            ]);
        }

        $batch = $transactions->toArray();
        $this->processBatchPredictions($batch);

        // Bungkus dengan DB Transaction agar 500 update queries dieksekusi secepat kilat!
        \DB::transaction(function () use ($batch) {
            foreach ($batch as $data) {
                if ($data['prediction'] !== null) {
                    TransactionRequest::where('id', $data['id'])->update([
                        'prediction' => $data['prediction'],
                        'confidence' => $data['confidence']
                    ]);
                }
            }
        });

        $remaining = TransactionRequest::where('is_imported', true)->whereNull('prediction')->count();

        return response()->json([
            'finished' => false,
            'processed' => count($batch),
            'remaining' => $remaining,
            'message' => "Berhasil memproses " . count($batch) . " data. Sisa: " . $remaining
        ]);
    }

    /**
     * Memproses prediksi DSS secara massal menggunakan Bulk Endpoint di Flask
     * Super cepat karena cuma 1 HTTP request untuk 100 baris data!
     */
    private function processBatchPredictions(&$batch)
    {
        try {
            $payload = array_map(function ($data) {
                return [
                    'sales' => $data['sales'],
                    'quantity' => $data['quantity'],
                    'discount' => $data['discount'],
                    'shipping_days' => $data['shipping_days'],
                    'category' => $data['category'],
                    'segment' => $data['segment'],
                    'region' => $data['region'],
                    'ship_mode' => $data['ship_mode']
                ];
            }, $batch);

            $response = \Illuminate\Support\Facades\Http::timeout(30)->post("{$this->api}/predict-profit-bulk", $payload);

            if ($response->successful()) {
                $results = $response->json();

                foreach ($results as $index => $res) {
                    if (!$res || isset($res['error'])) {
                        \Log::warning('Prediction Row Error: ' . json_encode($res));
                        $batch[$index]['prediction'] = 'Error'; // Set ke Error agar tidak looping terus
                        $batch[$index]['confidence'] = 0;
                        continue;
                    }

                    // Di model/DB kita simpan 1/0
                    $isProfitable = ($res['prediction'] ?? 0) == 1;
                    $batch[$index]['prediction'] = $isProfitable ? 1 : 0;

                    $prob = $res['prob_profitable'] ?? 0;
                    $batch[$index]['confidence'] = $prob;
                }
            } else {
                \Log::warning('Bulk API Error: ' . $response->body());
                foreach ($batch as &$b) {
                    $b['prediction'] = 'Error';
                    $b['confidence'] = 0;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Batch Prediction Exception: ' . $e->getMessage());
            foreach ($batch as &$b) {
                $b['prediction'] = 'Error';
                $b['confidence'] = 0;
            }
        }
    }
}
