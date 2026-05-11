<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Strategy;
use App\Models\TransactionRequest;

class DashboardController extends Controller
{
    private string $api = 'http://localhost:5000/api';

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

            'summary' => [],
            'monthly' => [],
            'yearly' => [],
            'category' => [],
            'region' => [],
            'segment' => [],
            'products' => [],

            'dashboardData' => [

                'role' => 'head-analytics',

                'monthly' => [],
                'yearly' => [],

                'category' => [],
                'region' => [],

                'segment' => [],

                'dssTrend' => $dssTrend,
            ],

            'analyticsMonitoring' => [

                'prediction_volume' =>
                $totalPredictions,

                'profitable_predictions' =>
                $profitablePredictions,

                'risky_predictions' =>
                $riskyPredictions,

                'avg_confidence' =>
                $avgConfidence,

                'prediction_accuracy' =>
                $predictionAccuracy,
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

        ]);
    }


    // ── Chief Logistics Officer ───────────────────────────────
    private function dashboardLogistics()
    {
        $summary = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];

        $region = Http::timeout(5)
            ->get("{$this->api}/sales-by-region")
            ->json() ?? [];

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
            'region'
        ) + [

            'role' => 'logistics-officer',

            'monthly' => [],
            'yearly' => [],
            'category' => [],
            'segment' => [],
            'products' => [],

            'dashboardData' => [

                'role' => 'logistics-officer',

                'monthly' => [],
                'yearly' => [],

                'category' => [],
                'region' => $region,

                'segment' => [],
            ],

            'logisticsAnalytics' => [

                'total_shipment' =>
                $totalShipment,

                'approved_shipment' =>
                $approvedShipment,

                'rejected_shipment' =>
                $rejectedShipment,

                'avg_confidence' =>
                $avgShipmentConfidence,

                'most_risky_ship_mode' =>
                $mostRiskyShipMode?->ship_mode
                    ?? '-',
            ],

            "Total shipment request tercatat sebanyak {$totalShipment}.",

            "Approved shipment mencapai {$approvedShipment} request.",

            "Rejected shipment mencapai {$rejectedShipment} request.",

            "Ship mode paling risky saat ini adalah " . ($mostRiskyShipMode?->ship_mode ?? '-') . ".",

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
            'category'
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

                'total_procurement' =>
                $totalProcurement,

                'approved_procurement' =>
                $approvedProcurement,

                'rejected_procurement' =>
                $rejectedProcurement,

                'avg_confidence' =>
                $avgProcurementConfidence,

                'most_rejected_category' =>
                $mostRejectedCategory?->category
                    ?? '-',
            ],

            'executiveInsights' => [

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

        $segment = array_filter(
            $segment,
            fn($s) =>
            in_array(
                $s['segment'],
                ['Corporate', 'Home Office']
            )
        );

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

                'avg_confidence' =>
                $avgContractConfidence,

                'top_region' =>
                $topContractRegion?->region
                    ?? '-',
            ],

            'executiveInsights' => [

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

        $requestTypeMap = [
            'procurement-director' => [
                'type'        => 'procurement',
                'title'       => 'Create Procurement Request',
                'description' => 'Ajukan pengadaan inventory & supplier procurement.',
            ],
            'logistics-officer' => [
                'type'        => 'shipment',
                'title'       => 'Create Shipment Request',
                'description' => 'Ajukan distribusi & shipment approval.',
            ],
            'key-account-manager' => [
                'type'        => 'contract',
                'title'       => 'Create Contract Request',
                'description' => 'Ajukan kontrak client & discount approval.',
            ],
        ];

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

            $requestData->update([
                'prediction' => $result['label_id'] ?? null,
                'confidence' => $result['confidence'] ?? null,
            ]);
        } catch (\Exception $e) {
            $result = null;
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
            ->with('success', 'Request berhasil di-reject.');
    }

    public function transactionHistory()
    {
        $role = auth()->user()->roles->first()?->name;

        $query = TransactionRequest::latest()
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['requester', 'approver']);

        if ($role === 'procurement-director') {
            $query->where('request_type', 'procurement');
        } elseif ($role === 'logistics-officer') {
            $query->where('request_type', 'shipment');
        } elseif ($role === 'key-account-manager') {
            $query->where('request_type', 'contract');
        }
        // financial-controller sees all

        $transactions = $query->paginate(15);

        return view('transactions.history', compact('transactions'));
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
            default                => null, // financial-controller sees all
        };

        return $query->take(5)->get()->map(function ($item) {
            $statusLabel = $item->status === 'approved' ? 'disetujui' : 'ditolak';
            $prediction  = $item->prediction ? " Prediksi DSS: {$item->prediction}." : '';
            $confidence  = $item->confidence ? " Confidence: {$item->confidence}%." : '';

            return [
                'title'      => $item->title,
                'status'     => $item->status,
                'created_at' => $item->created_at,
                'message'    => ucfirst($item->request_type) . " request via {$item->ship_mode} telah {$statusLabel}.{$prediction}{$confidence}",
            ];
        });
    }
}
