<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Strategy;
use App\Models\TransactionRequest;

class DashboardController extends Controller
{
    private string $api = 'http://127.0.0.1:5000/api';

    public function index()
    {
        /** @var \App\Models\User $user */
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
        $summary  = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];
        $monthly  = Http::timeout(5)->get("{$this->api}/monthly-trend")->json() ?? [];
        $yearly   = Http::timeout(5)->get("{$this->api}/yearly-trend")->json() ?? [];
        $category = Http::timeout(5)->get("{$this->api}/profit-by-category")->json() ?? [];
        $region   = Http::timeout(5)->get("{$this->api}/sales-by-region")->json() ?? [];
        $segment  = Http::timeout(5)->get("{$this->api}/sales-by-segment")->json() ?? [];
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
        SUM(CASE WHEN prediction = "Profitable" THEN 1 ELSE 0 END) as profitable,
        SUM(CASE WHEN prediction = "Loss" THEN 1 ELSE 0 END) as risky
    ')

            ->groupBy('date')

            ->orderBy('date')

            ->take(10)

            ->get();

        return view('dashboard.index', [

            'role' => 'head-analytics',

            'summary'  => $summary,
            'monthly'  => $monthly,
            'yearly'   => $yearly,
            'category' => $category,
            'region'   => $region,
            'segment'  => $segment,
            'products' => $products,

            'dashboardData' => [
                'role'     => 'head-analytics',
                'monthly'  => $monthly,
                'yearly'   => $yearly,
                'category' => $category,
                'region'   => $region,
                'segment'  => $segment,
                'dssTrend' => $dssTrend,
            ],

            'analyticsMonitoring' => [
                'prediction_volume'    => $totalPredictions,
                'profitable_predictions' => $profitablePredictions,
                'risky_predictions'    => $riskyPredictions,
                'avg_confidence'       => $avgConfidence,
                'prediction_accuracy'  => $predictionAccuracy,
                'health_status'        => $avgConfidence >= 75 ? 'Stable' : 'Monitoring Required',
                'estimated_accuracy'   => $predictionAccuracy,
            ],

            'executiveInsights' => [
                "Total prediction processed DSS mencapai {$totalPredictions}.",
                "Prediction profitable mencapai {$profitablePredictions}.",
                "Prediction risky/loss mencapai {$riskyPredictions}.",
                "Average confidence DSS berada di angka {$avgConfidence}%.",
            ],

            'intelligenceFeed' =>
            $this->getIntelligenceFeed(
                'head-analytics'
            ),

        ]);
    }

    // ── Financial Controller ──────────────────────────────────
    private function dashboardFinance()
    {
        $summary  = Http::timeout(5)
            ->get("{$this->api}/summary")
            ->json() ?? [];

        $region   = Http::timeout(5)
            ->get("{$this->api}/sales-by-region")
            ->json() ?? [];

        $category = Http::timeout(5)
            ->get("{$this->api}/profit-by-category")
            ->json() ?? [];

        $yearly   = Http::timeout(5)
            ->get("{$this->api}/yearly-trend")
            ->json() ?? [];

        /*
|--------------------------------------------------------------------------
| Dashboard Filters
|--------------------------------------------------------------------------
*/

        $statusFilter = request('status');

        $periodFilter = request('period');

        /*
|--------------------------------------------------------------------------
| Base Query
|--------------------------------------------------------------------------
*/

        $query = TransactionRequest::query();

        /*
|--------------------------------------------------------------------------
| Status Filter
|--------------------------------------------------------------------------
*/

        if ($statusFilter) {

            $query->where(
                'status',
                $statusFilter
            );
        }

        /*
|--------------------------------------------------------------------------
| Period Filter
|--------------------------------------------------------------------------
*/

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
| DSS Analytics
|--------------------------------------------------------------------------
*/

        $totalTransactions =
            (clone $query)->count();

        $approvedCount =

            (clone $query)

            ->where(
                'status',
                'approved'
            )

            ->count();

        $rejectedCount =

            (clone $query)

            ->where(
                'status',
                'rejected'
            )

            ->count();

        /*
|--------------------------------------------------------------------------
| Rates
|--------------------------------------------------------------------------
*/

        $approvalRate =

            $totalTransactions > 0

            ? round(
                ($approvedCount / $totalTransactions) * 100,
                1
            )

            : 0;

        $rejectionRate =

            $totalTransactions > 0

            ? round(
                ($rejectedCount / $totalTransactions) * 100,
                1
            )

            : 0;

        /*
|--------------------------------------------------------------------------
| Average Confidence
|--------------------------------------------------------------------------
*/

        $avgConfidence = round(

            (clone $query)

                ->whereNotNull(
                    'confidence'
                )

                ->avg('confidence'),

            1
        );

        /*
|--------------------------------------------------------------------------
| Most Risky Category
|--------------------------------------------------------------------------
*/

        $riskyCategory =

            (clone $query)

            ->where(
                'status',
                'rejected'
            )

            ->selectRaw('category, COUNT(*) as total')

            ->groupBy('category')

            ->orderByDesc('total')

            ->first();

        /*
|--------------------------------------------------------------------------
| Most Risky Ship Mode
|--------------------------------------------------------------------------
*/

        $riskyShipMode =

            (clone $query)

            ->where(
                'status',
                'rejected'
            )

            ->selectRaw('ship_mode, COUNT(*) as total')

            ->groupBy('ship_mode')

            ->orderByDesc('total')

            ->first();

        /*
|--------------------------------------------------------------------------
| DSS Decision Trend
|--------------------------------------------------------------------------
*/

        $dssTrend =

            (clone $query)

            ->selectRaw('
        DATE(created_at) as date,
        SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
    ')

            ->groupBy('date')

            ->orderBy('date')

            ->take(10)

            ->get();

        return view('dashboard.index', compact(
            'summary',
            'region',
            'category',
            'yearly'
        ) + [

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

                'approval_rate' =>
                $approvalRate,

                'rejection_rate' =>
                $rejectionRate,

                'avg_confidence' =>
                $avgConfidence,

                'risky_category' =>
                $riskyCategory?->category
                    ?? '-',

                'risky_ship_mode' =>
                $riskyShipMode?->ship_mode
                    ?? '-',
            ],

            'executiveInsights' => [

                "Approval rate mencapai {$approvalRate}% dari seluruh transaksi DSS.",

                "Rata-rata confidence DSS berada di angka {$avgConfidence}% untuk seluruh approval prediction.",

                "Kategori paling berisiko saat ini adalah " . ($riskyCategory?->category ?? '-') . ".",

                "Ship mode paling sering mengalami reject adalah " . ($riskyShipMode?->ship_mode ?? '-') . ".",

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
            $region  = Http::timeout(15)->get("{$this->api}/sales-by-region")->json() ?? [];
            $yearly  = Http::timeout(15)->get("{$this->api}/yearly-trend")->json() ?? [];
        } catch (\Exception $e) {
            $summary = [];
            $region  = [];
            $yearly  = [];
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
                'total_shipment'       => $totalShipment,
                'approved_shipment'    => $approvedShipment,
                'rejected_shipment'    => $rejectedShipment,
                'approval_rate'        => $totalShipment > 0
                    ? round(($approvedShipment / $totalShipment) * 100, 1)
                    : 0,
                'avg_confidence'       => $avgShipmentConfidence,
                'risky_ship_mode'      => $mostRiskyShipMode?->ship_mode ?? '-', // ← ganti key ini
            ],

            'logisticsInsights' => [ // ← key ini yang tadi hilang
                "Total shipment request tercatat sebanyak {$totalShipment}.",
                "Approved shipment mencapai {$approvedShipment} request.",
                "Rejected shipment mencapai {$rejectedShipment} request.",
                "Ship mode paling risky saat ini adalah " . ($mostRiskyShipMode?->ship_mode ?? '-') . ".",
            ],

            'intelligenceFeed' =>
            $this->getIntelligenceFeed(
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
                'total_procurement'    => $totalProcurement,
                'approved_procurement' => $approvedProcurement,
                'rejected_procurement' => $rejectedProcurement,
                'approval_rate'        => $totalProcurement > 0
                    ? round(($approvedProcurement / $totalProcurement) * 100, 1)
                    : 0,
                'avg_confidence'       => $avgProcurementConfidence,
                'risky_category'       => $mostRejectedCategory?->category ?? '-', // ← ganti key ini
            ],

            'procurementInsights' => [
                "Total procurement request tercatat sebanyak {$totalProcurement}.",
                "Approved procurement mencapai {$approvedProcurement} request.",
                "Rejected procurement mencapai {$rejectedProcurement} request.",
                "Kategori procurement paling sering ditolak adalah " . ($mostRejectedCategory?->category ?? '-') . ".",
            ],

            'intelligenceFeed' =>
            $this->getIntelligenceFeed(
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
            fn($s) =>
            in_array(
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

                'total_contracts' =>
                $totalContracts,

                'approved_contracts' =>
                $approvedContracts,

                'rejected_contracts' =>
                $rejectedContracts,

                'approval_rate' =>
                $totalContracts > 0
                    ? round(($approvedContracts / $totalContracts) * 100, 1)
                    : 0,

                'avg_confidence' =>
                $avgContractConfidence,

                'top_segment' =>
                collect($segment)->sortByDesc('total_sales')->first()['segment'] ?? '-',

                'top_region' =>
                $topContractRegion?->region
                    ?? '-',
            ],

            'kamInsights' => [
                "Total contract request tercatat sebanyak {$totalContracts}.",
                "Approved contracts mencapai {$approvedContracts}.",
                "Rejected contracts mencapai {$rejectedContracts}.",
                "Region kontrak tertinggi saat ini adalah " . ($topContractRegion?->region ?? '-') . ".",
            ],

            'intelligenceFeed' =>
            $this->getIntelligenceFeed(
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
            'sales'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1|max:14',
            'discount'      => 'required|numeric|min:0|max:0.8',
            'shipping_days' => 'required|integer|min:0|max:7',
            'category'      => 'required|in:Furniture,Office Supplies,Technology',
            'segment'       => 'required|in:Consumer,Corporate,Home Office',
            'region'        => 'required|in:East,West,Central,South',
            'ship_mode'     => 'required|in:First Class,Second Class,Standard Class,Same Day',
        ]);

        try {
            $response = Http::timeout(5)->post("{$this->api}/predict-profit", [
                'sales'         => (float) $request->sales,
                'quantity'      => (int)   $request->quantity,
                'discount'      => (float) $request->discount,
                'shipping_days' => (int)   $request->shipping_days,
                'category'      => $request->category,
                'segment'       => $request->segment,
                'region'        => $request->region,
                'ship_mode'     => $request->ship_mode,
            ]);

            $result     = $response->json();
            $prediction = $result['prediction'] ?? 'Unknown';
            $confidence = $result['confidence'] ?? 0;

            if ($prediction === 'Loss') {
                Strategy::create([
                    'target_role'    => 'logistics-officer',
                    'title'          => 'Optimasi Pengiriman',
                    'recommendation' => 'Gunakan Standard Class untuk menekan biaya distribusi.',
                    'prediction'     => $prediction,
                    'confidence'     => $confidence,
                ]);

                Strategy::create([
                    'target_role'    => 'procurement-director',
                    'title'          => 'Batasi Margin Procurement',
                    'recommendation' => 'Kurangi pembelian pada kategori dengan margin rendah.',
                    'prediction'     => $prediction,
                    'confidence'     => $confidence,
                ]);

                Strategy::create([
                    'target_role'    => 'key-account-manager',
                    'title'          => 'Batasi Diskon Client',
                    'recommendation' => 'Hindari pemberian diskon tinggi pada kontrak baru.',
                    'prediction'     => $prediction,
                    'confidence'     => $confidence,
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Flask API tidak dapat dihubungi.']);
        }

        return view('dashboard.dss', [
            'result' => $result,
            'input'  => $request->all(),
        ]);
    }

    public function createRequest()
    {
        $role = auth()->user()->roles->first()?->name;
        $requestTypeMap = $this->getRequestConfig($role);
        $requestMeta = $requestTypeMap[$role] ?? null;
        abort_if(!$requestMeta, 403);

        return view('requests.create', compact('requestMeta'));
    }

    public function storeRequest(Request $request)
    {
        $role = auth()->user()->roles->first()?->name;

        $requestTypeMap = [
            'procurement-director' => 'procurement',
            'logistics-officer'    => 'shipment',
            'key-account-manager'  => 'contract',
        ];

        $request->validate([
            'title'         => 'required|max:255',
            'description'   => 'nullable',
            'sales'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
            'discount'      => 'required|numeric|min:0|max:0.8',
            'shipping_days' => 'required|integer|min:0|max:7',
            'category'      => 'required',
            'segment'       => 'required',
            'region'        => 'required',
            'ship_mode'     => 'required',
        ]);

        TransactionRequest::create([
            'requester_id'  => auth()->id(),
            'request_type'  => $requestTypeMap[$role] ?? 'unknown',
            'title'         => $request->title,
            'description'   => $request->description,
            'sales'         => $request->sales,
            'quantity'      => $request->quantity,
            'discount'      => $request->discount,
            'shipping_days' => $request->shipping_days,
            'category'      => $request->category,
            'segment'       => $request->segment,
            'region'        => $request->region,
            'ship_mode'     => $request->ship_mode,
            'status'        => 'pending',
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
        abort_if(!$requestMeta, 403);

        return view('requests.edit', compact('requestMeta', 'requestItem'));
    }

    public function updateRequest(Request $request, $id)
    {
        $requestItem = TransactionRequest::findOrFail($id);

        // Security check
        abort_if($requestItem->requester_id !== auth()->id(), 403, 'Unauthorized access.');
        abort_if($requestItem->status !== 'pending', 403, 'Hanya request pending yang bisa diupdate.');

        $request->validate([
            'title'         => 'required|max:255',
            'description'   => 'nullable',
            'sales'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
            'discount'      => 'required|numeric|min:0|max:0.8',
            'shipping_days' => 'required|integer|min:0|max:7',
            'category'      => 'required',
            'segment'       => 'required',
            'region'        => 'required',
            'ship_mode'     => 'required',
        ]);

        $requestItem->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'sales'         => $request->sales,
            'quantity'      => $request->quantity,
            'discount'      => $request->discount,
            'shipping_days' => $request->shipping_days,
            'category'      => $request->category,
            'segment'       => $request->segment,
            'region'        => $request->region,
            'ship_mode'     => $request->ship_mode,
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
                'type'        => 'procurement',
                'title'       => 'Procurement Request',
                'description' => 'Edit pengadaan inventory & supplier procurement.',
                'fields'      => [
                    'sales'         => ['show' => true,  'label' => 'Estimated Cost ($)'],
                    'quantity'      => ['show' => true,  'label' => 'Order Quantity'],
                    'discount'      => ['show' => false, 'default' => 0.0],
                    'shipping_days' => ['show' => false, 'default' => 4],
                    'category'      => ['show' => true,  'label' => 'Product Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment'       => ['show' => false, 'default' => 'Consumer'],
                    'region'        => ['show' => true,  'label' => 'Supplier Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode'     => ['show' => false, 'default' => 'Standard Class'],
                ],
            ],
            'logistics-officer' => [
                'type'        => 'shipment',
                'title'       => 'Shipment Request',
                'description' => 'Edit distribusi & shipment approval.',
                'fields'      => [
                    'sales'         => ['show' => true,  'label' => 'Shipment Value ($)'],
                    'quantity'      => ['show' => true,  'label' => 'Package Quantity'],
                    'discount'      => ['show' => false, 'default' => 0.0],
                    'shipping_days' => ['show' => true,  'label' => 'Estimasi Hari Kirim'],
                    'category'      => ['show' => true,  'label' => 'Product Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment'       => ['show' => true,  'label' => 'Customer Segment', 'options' => ['Consumer', 'Corporate', 'Home Office']],
                    'region'        => ['show' => true,  'label' => 'Destination Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode'     => ['show' => true,  'label' => 'Ship Mode', 'options' => ['First Class', 'Second Class', 'Standard Class', 'Same Day']],
                ],
            ],
            'key-account-manager' => [
                'type'        => 'contract',
                'title'       => 'Contract Request',
                'description' => 'Edit kontrak client & discount approval.',
                'fields'      => [
                    'sales'         => ['show' => true,  'label' => 'Nilai Kontrak ($)'],
                    'quantity'      => ['show' => true,  'label' => 'Jumlah Item'],
                    'discount'      => ['show' => true,  'label' => 'Diskon Klien'],
                    'shipping_days' => ['show' => false, 'default' => 4],
                    'category'      => ['show' => true,  'label' => 'Product Category', 'options' => ['Furniture', 'Office Supplies', 'Technology']],
                    'segment'       => ['show' => true,  'label' => 'Client Segment', 'options' => ['Corporate', 'Home Office']],
                    'region'        => ['show' => true,  'label' => 'Client Region', 'options' => ['East', 'West', 'Central', 'South']],
                    'ship_mode'     => ['show' => false, 'default' => 'Standard Class'],
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
                'sales'         => (float) $requestData->sales,
                'quantity'      => (int)   $requestData->quantity,
                'discount'      => (float) $requestData->discount,
                'shipping_days' => (int)   $requestData->shipping_days,
                'category'      => $requestData->category,
                'segment'       => $requestData->segment,
                'region'        => $requestData->region,
                'ship_mode'     => $requestData->ship_mode,
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

    public function approveRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);

        try {
            $response = Http::timeout(10)->post("{$this->api}/predict-profit", [
                'sales'         => (float) $requestData->sales,
                'quantity'      => (int)   $requestData->quantity,
                'discount'      => (float) $requestData->discount,
                'shipping_days' => (int)   $requestData->shipping_days,
                'category'      => $requestData->category,
                'segment'       => $requestData->segment,
                'region'        => $requestData->region,
                'ship_mode'     => $requestData->ship_mode,
            ]);

            $result = $response->json();
        } catch (\Exception $e) {
            $result = null;
        }

        $requestData->update([
            'status'      => 'approved',
            'prediction'  => $result['label_id'] ?? null,
            'confidence'  => $result['prob_profitable'] ?? null,
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
                'sales'         => (float) $requestData->sales,
                'quantity'      => (int)   $requestData->quantity,
                'discount'      => (float) $requestData->discount,
                'shipping_days' => (int)   $requestData->shipping_days,
                'category'      => $requestData->category,
                'segment'       => $requestData->segment,
                'region'        => $requestData->region,
                'ship_mode'     => $requestData->ship_mode,
            ]);

            $result = $response->json();
        } catch (\Exception $e) {
            $result = null;
        }

        $requestData->update([
            'status'      => 'rejected',
            'prediction'  => $result['label_id'] ?? null,
            'confidence'  => $result['prob_profitable'] ?? null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('requests.pending')
            ->with('error', 'Request telah ditolak.');
    }

    public function transactionHistory()
    {
        $role = auth()->user()->roles->first()?->name;

        // ── DSS Requests ─────────────────────────────────────────
        $query = TransactionRequest::latest()
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->with(['requester', 'approver']);

        if ($role === 'procurement-director') {
            $query->where('request_type', 'procurement');
        } elseif ($role === 'logistics-officer') {
            $query->where('request_type', 'shipment');
        } elseif ($role === 'key-account-manager') {
            $query->where('request_type', 'contract');
        }

        $transactions = $query->paginate(15);

        // Ambil config field untuk modal edit di view
        $requestTypeMap = $this->getRequestConfig($role);
        $requestMeta = $requestTypeMap[$role] ?? null;

        // ── Historical Orders dari Flask API ──────────────────────
        $historicalPage = (int) request('historical_page', 1);
        $category       = request('category', '');
        $region         = request('region', '');
        $segment        = request('segment', '');

        try {
            $response = Http::timeout(10)->get("{$this->api}/orders", [
                'page'     => $historicalPage,
                'per_page' => 15,
                'category' => $category,
                'region'   => $region,
                'segment'  => $segment,
            ])->json();

            $historicalOrders   = $response['data']      ?? [];
            $historicalTotal    = $response['total']     ?? 0;
            $historicalLastPage = $response['last_page'] ?? 1;
        } catch (\Exception $e) {
            $historicalOrders   = [];
            $historicalTotal    = 0;
            $historicalLastPage = 1;
        }

        return view('transactions.history', compact(
            'transactions',
            'historicalOrders',
            'historicalTotal',
            'historicalLastPage',
            'historicalPage',
            'role',
            'requestMeta'
        ));
    }

    public function exportTransactions()
    {
        $transactions = TransactionRequest::latest()
            ->with(['requester', 'approver'])
            ->get();

        $filename = 'transaction-report-' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
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

    public function exportAnalyticsReport()
    {
        $transactions     = TransactionRequest::all();
        $totalPredictions = $transactions->count();
        $approved         = $transactions->where('status', 'approved')->count();
        $rejected         = $transactions->where('status', 'rejected')->count();
        $avgConfidence    = round($transactions->avg('confidence'), 1);

        $filename = 'dss-monitoring-report-' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
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

    // ── Intelligence Feed ─────────────────────────────────────
    private function getIntelligenceFeed(string $role): \Illuminate\Support\Collection
    {
        $query = TransactionRequest::latest();


        match ($role) {
            'procurement-director' => $query->where('request_type', 'procurement'),
            'logistics-officer'    => $query->where('request_type', 'shipment'),
            'key-account-manager'  => $query->where('request_type', 'contract'),
            default                => $query,
        };

        return $query->take(5)->get()->map(function ($item) {
            $statusLabel = match ($item->status) {
                'approved' => 'disetujui',
                'rejected' => 'ditolak',
                default    => 'menunggu review',
            };
            $prediction  = $item->prediction ? " Prediksi DSS: {$item->prediction}." : '';
            $confidence  = $item->confidence ? " Confidence: {$item->confidence}%." : '';

            $verb = $item->status === 'pending' ? 'sedang' : 'telah';

            return [
                'title'      => $item->title,
                'status'     => $item->status,
                'created_at' => $item->created_at,
                'message'    => ucfirst($item->request_type) . " request via {$item->ship_mode} {$verb} {$statusLabel}.{$prediction}{$confidence}",
            ];
        });
    }
}
