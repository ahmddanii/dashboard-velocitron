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
            // Data yang diambil berbeda per role
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
    // Lihat semua data — full access
    private function dashboardAnalytics()
    {
        $summary  = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];
        $monthly  = Http::timeout(5)->get("{$this->api}/monthly-trend")->json() ?? [];
        $yearly   = Http::timeout(5)->get("{$this->api}/yearly-trend")->json() ?? [];
        $category = Http::timeout(5)->get("{$this->api}/profit-by-category")->json() ?? [];
        $region   = Http::timeout(5)->get("{$this->api}/sales-by-region")->json() ?? [];
        $segment  = Http::timeout(5)->get("{$this->api}/sales-by-segment")->json() ?? [];
        $products = Http::timeout(5)->get("{$this->api}/top-products")->json() ?? [];

        return view('dashboard.index', compact(
            'summary',
            'monthly',
            'yearly',
            'category',
            'region',
            'segment',
            'products'
        ) + [

            'role' => 'head-analytics',

            'dashboardData' => [

                'role' => 'head-analytics',

                'monthly' => $monthly,
                'yearly' => $yearly,

                'category' => $category,
                'region' => $region,

                'segment' => $segment,
            ]
        ]);
    }

    // ── Financial Controller ──────────────────────────────────
    // Fokus: Profit, Discount, per Region
    private function dashboardFinance()
    {
        $summary  = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];
        $region   = Http::timeout(5)->get("{$this->api}/sales-by-region")->json() ?? [];
        $category = Http::timeout(5)->get("{$this->api}/profit-by-category")->json() ?? [];
        $yearly   = Http::timeout(5)->get("{$this->api}/yearly-trend")->json() ?? [];


        /*
|--------------------------------------------------------------------------
| DSS Analytics
|--------------------------------------------------------------------------
*/

        $totalTransactions = TransactionRequest::count();

        $approvedCount = TransactionRequest::where(
            'status',
            'approved'
        )->count();

        $rejectedCount = TransactionRequest::where(
            'status',
            'rejected'
        )->count();

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

            TransactionRequest::whereNotNull(
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

        $riskyCategory = TransactionRequest::where(
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

        $riskyShipMode = TransactionRequest::where(
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

        $dssTrend = TransactionRequest::selectRaw('
        DATE(created_at) as date,
        SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
    ')

            ->groupBy('date')

            ->orderBy('date')

            ->take(10)

            ->get();

        /*
|--------------------------------------------------------------------------
| Executive Insight Narrative
|--------------------------------------------------------------------------
*/

        $executiveInsights = [];

        /*
|--------------------------------------------------------------------------
| Approval Insight
|--------------------------------------------------------------------------
*/

        if ($approvalRate >= 70) {

            $executiveInsights[] =

                "Approval rate DSS saat ini stabil di {$approvalRate}%, menunjukkan mayoritas transaksi masih berada dalam margin aman.";
        } elseif ($approvalRate >= 50) {

            $executiveInsights[] =

                "Approval rate berada di level moderat ({$approvalRate}%). Beberapa transaksi mulai menunjukkan peningkatan risiko profitabilitas.";
        } else {

            $executiveInsights[] =

                "Approval rate rendah ({$approvalRate}%). DSS mendeteksi peningkatan transaksi berisiko tinggi.";
        }

        /*
|--------------------------------------------------------------------------
| Risky Category Insight
|--------------------------------------------------------------------------
*/

        if ($riskyCategory) {

            $executiveInsights[] =

                "Kategori {$riskyCategory->category} menjadi sumber rejection tertinggi berdasarkan historical DSS decisions.";
        }

        /*
|--------------------------------------------------------------------------
| Ship Mode Insight
|--------------------------------------------------------------------------
*/

        if ($riskyShipMode) {

            $executiveInsights[] =

                "Ship mode {$riskyShipMode->ship_mode} menunjukkan tingkat risiko operasional tertinggi.";
        }

        /*
|--------------------------------------------------------------------------
| Confidence Insight
|--------------------------------------------------------------------------
*/

        if ($avgConfidence >= 75) {

            $executiveInsights[] =

                "Confidence DSS berada di level tinggi ({$avgConfidence}%), menunjukkan stabilitas prediksi model.";
        } else {

            $executiveInsights[] =

                "Confidence DSS masih berada di level moderat ({$avgConfidence}%). Monitoring tambahan direkomendasikan.";
        }

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
                'dss_trend' => $dssTrend,
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

            'executiveInsights' =>
            $executiveInsights,

        ]);
    }

    // ── Chief Logistics Officer ───────────────────────────────
    // Fokus: Ship Mode, Shipping Days, distribusi region
    private function dashboardLogistics()
    {
        $summary = Http::timeout(5)
            ->get("{$this->api}/summary")
            ->json() ?? [];

        $region = Http::timeout(5)
            ->get("{$this->api}/sales-by-region")
            ->json() ?? [];

        $yearly = Http::timeout(5)
            ->get("{$this->api}/yearly-trend")
            ->json() ?? [];

        /*
    |--------------------------------------------------------------------------
    | DSS Intelligence Feed
    |--------------------------------------------------------------------------
    */

        $strategies = Strategy::latest()

            ->where(
                'target_role',
                'logistics-officer'
            )

            ->take(5)

            ->get();

        return view('dashboard.index', compact(
            'summary',
            'region',
            'yearly',
            'strategies'
        ) + [

            'role' => 'logistics-officer',

            'monthly' => [],
            'category' => [],
            'segment' => [],
            'products' => [],
            'intelligenceFeed' =>
            $this->getIntelligenceFeed(
                'logistics-officer'
            ),

            'dashboardData' => [

                'role' => 'logistics-officer',

                'monthly' => [],
                'yearly' => $yearly,

                'category' => [],
                'region' => $region,

                'segment' => [],

            ]
        ]);
    }

    // ── Director of Strategic Procurement ────────────────────
    // Fokus: Kategori Technology & Furniture

    private function dashboardProcurement()
    {
        $summary = Http::timeout(5)
            ->get("{$this->api}/summary")
            ->json() ?? [];

        $category = Http::timeout(5)
            ->get("{$this->api}/profit-by-category")
            ->json() ?? [];

        $products = Http::timeout(5)
            ->get("{$this->api}/top-products")
            ->json() ?? [];

        /*
    |--------------------------------------------------------------------------
    | Filter Category
    |--------------------------------------------------------------------------
    */

        $category = array_filter(
            $category,
            fn($c) =>
            in_array(
                $c['category'],
                ['Technology', 'Furniture']
            )
        );

        /*
    |--------------------------------------------------------------------------
    | DSS Intelligence Feed
    |--------------------------------------------------------------------------
    */

        $strategies = Strategy::latest()

            ->where(
                'target_role',
                'procurement-director'
            )

            ->take(5)

            ->get();

        /*
|--------------------------------------------------------------------------
| Procurement DSS Analytics
|--------------------------------------------------------------------------
*/

        $totalProcurement = TransactionRequest::where(
            'request_type',
            'procurement'
        )->count();

        $approvedProcurement = TransactionRequest::where(
            'request_type',
            'procurement'
        )

            ->where(
                'status',
                'approved'
            )

            ->count();

        /*
|--------------------------------------------------------------------------
| Approval Rate
|--------------------------------------------------------------------------
*/

        $procurementApprovalRate =

            $totalProcurement > 0

            ? round(
                ($approvedProcurement / $totalProcurement) * 100,
                1
            )

            : 0;

        /*
|--------------------------------------------------------------------------
| Most Rejected Procurement Category
|--------------------------------------------------------------------------
*/

        $mostRejectedCategory = TransactionRequest::where(
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

        /*
|--------------------------------------------------------------------------
| Procurement Insights
|--------------------------------------------------------------------------
*/

        $procurementInsights = [];

        if ($procurementApprovalRate >= 70) {

            $procurementInsights[] =

                "Procurement approval rate stabil di {$procurementApprovalRate}%, menunjukkan supply planning masih berada dalam margin aman.";
        } else {

            $procurementInsights[] =

                "Procurement rejection meningkat. DSS mendeteksi potensi risiko profitabilitas pada beberapa pengadaan.";
        }

        if ($mostRejectedCategory) {

            $procurementInsights[] =

                "Kategori {$mostRejectedCategory->category} menjadi procurement category dengan rejection tertinggi.";
        }

        return view('dashboard.index', compact(
            'summary',
            'category',
            'products',
            'strategies'
        ) + [

            'role' => 'procurement-director',

            'monthly' => [],
            'yearly' => [],
            'region' => [],
            'segment' => [],
            'intelligenceFeed' =>
            $this->getIntelligenceFeed(
                'procurement-director'
            ),
            'procurementAnalytics' => [

                'approval_rate' =>
                $procurementApprovalRate,

                'risky_category' =>
                $mostRejectedCategory?->category
                    ?? '-',
            ],

            'procurementInsights' =>
            $procurementInsights,
            'dashboardData' => [

                'role' => 'procurement-director',

                'monthly' => [],
                'yearly' => [],

                'category' => $category,
                'region' => [],

                'segment' => [],

            ]
        ]);
    }

    // ── Key Account Manager ───────────────────────────────────
    // Fokus: Segmen Corporate & Home Office
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
            $this->getIntelligenceFeed(
                'key-account-manager'
            ),

            'dashboardData' => [

                'role' => 'key-account-manager',

                'monthly' => [],
                'yearly' => [],

                'category' => [],
                'region' => $region,

                'segment' => $segment,


            ]
        ]);
    }

    // ── DSS (hanya head-analytics & financial-controller) ────
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

            $response = Http::timeout(5)
                ->post("{$this->api}/predict-profit", [

                    'sales' => (float) $request->sales,

                    'quantity' =>
                    (int) $request->quantity,

                    'discount' =>
                    (float) $request->discount,

                    'shipping_days' =>
                    (int) $request->shipping_days,

                    'category' =>
                    $request->category,

                    'segment' =>
                    $request->segment,

                    'region' =>
                    $request->region,

                    'ship_mode' =>
                    $request->ship_mode,
                ]);

            $result = $response->json();

            $prediction =
                $result['prediction']
                ?? 'Unknown';

            $confidence =
                $result['confidence']
                ?? 0;

            /*
        |--------------------------------------------------------------------------
        | DSS Recommendation Engine
        |--------------------------------------------------------------------------
        */

            if ($prediction === 'Loss') {

                Strategy::create([

                    'target_role' =>
                    'logistics-officer',

                    'title' =>
                    'Optimasi Pengiriman',

                    'recommendation' =>
                    'Gunakan Standard Class untuk menekan biaya distribusi.',

                    'prediction' =>
                    $prediction,

                    'confidence' =>
                    $confidence,
                ]);

                Strategy::create([

                    'target_role' =>
                    'procurement-director',

                    'title' =>
                    'Batasi Margin Procurement',

                    'recommendation' =>
                    'Kurangi pembelian pada kategori dengan margin rendah.',

                    'prediction' =>
                    $prediction,

                    'confidence' =>
                    $confidence,
                ]);

                Strategy::create([

                    'target_role' =>
                    'key-account-manager',

                    'title' =>
                    'Batasi Diskon Client',

                    'recommendation' =>
                    'Hindari pemberian diskon tinggi pada kontrak baru.',

                    'prediction' =>
                    $prediction,

                    'confidence' =>
                    $confidence,
                ]);
            }
        } catch (\Exception $e) {

            return back()->withErrors([
                'api' =>
                'Flask API tidak dapat dihubungi.'
            ]);
        }

        return view('dashboard.dss', [

            'result' => $result,

            'input' =>
            $request->all(),
        ]);
    }

    public function createRequest()
    {
        $role = auth()->user()

            ->roles

            ->first()

            ?->name;

        /*
        |--------------------------------------------------------------------------
        | Role Mapping
        |--------------------------------------------------------------------------
        */

        $requestTypeMap = [

            'procurement-director' => [

                'type' =>
                'procurement',

                'title' =>
                'Create Procurement Request',

                'description' =>
                'Ajukan pengadaan inventory & supplier procurement.',
            ],

            'logistics-officer' => [

                'type' =>
                'shipment',

                'title' =>
                'Create Shipment Request',

                'description' =>
                'Ajukan distribusi & shipment approval.',
            ],

            'key-account-manager' => [

                'type' =>
                'contract',

                'title' =>
                'Create Contract Request',

                'description' =>
                'Ajukan kontrak client & discount approval.',
            ],
        ];

        $requestMeta =

            $requestTypeMap[$role]

            ?? null;

        abort_if(!$requestMeta, 403);

        return view(
            'requests.create',
            compact(
                'requestMeta'
            )
        );
    }

    public function storeRequest(Request $request)
    {

        $role = auth()->user()

            ->roles

            ->first()

            ?->name;

        $requestTypeMap = [

            'procurement-director' =>
            'procurement',

            'logistics-officer' =>
            'shipment',

            'key-account-manager' =>
            'contract',
        ];


        $request->validate([

            'title' =>
            'required|max:255',

            'description' =>
            'nullable',

            'sales' =>
            'required|numeric|min:0',

            'quantity' =>
            'required|integer|min:1',

            'discount' =>
            'required|numeric|min:0|max:0.8',

            'shipping_days' =>
            'required|integer|min:0|max:7',

            'category' =>
            'required',

            'segment' =>
            'required',

            'region' =>
            'required',

            'ship_mode' =>
            'required',
        ]);

        TransactionRequest::create([

            'requester_id' =>
            auth()->id(),

            'request_type' =>

            $requestTypeMap[$role]
                ?? 'unknown',

            'title' =>
            $request->title,

            'description' =>
            $request->description,

            'sales' =>
            $request->sales,

            'quantity' =>
            $request->quantity,

            'discount' =>
            $request->discount,

            'shipping_days' =>
            $request->shipping_days,

            'category' =>
            $request->category,

            'segment' =>
            $request->segment,

            'region' =>
            $request->region,

            'ship_mode' =>
            $request->ship_mode,

            'status' =>
            'pending',
        ]);

        return redirect()

            ->route('dashboard')

            ->with(
                'success',
                'Request berhasil diajukan ke Financial Controller.'
            );
    }

    public function pendingRequests()
    {
        $requests = TransactionRequest::latest()

            ->where('status', 'pending')

            ->with('requester')

            ->get();

        return view(
            'requests.pending',
            compact('requests')
        );
    }

    public function reviewRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | DSS Prediction Request
    |--------------------------------------------------------------------------
    */

        try {

            $response = Http::timeout(10)

                ->post("{$this->api}/predict-profit", [

                    'sales' =>
                    (float) $requestData->sales,

                    'quantity' =>
                    (int) $requestData->quantity,

                    'discount' =>
                    (float) $requestData->discount,

                    'shipping_days' =>
                    (int) $requestData->shipping_days,

                    'category' =>
                    $requestData->category,

                    'segment' =>
                    $requestData->segment,

                    'region' =>
                    $requestData->region,

                    'ship_mode' =>
                    $requestData->ship_mode,
                ]);


            $requestData->update([

                'prediction' =>
                $result['label_id'] ?? null,

                'confidence' =>
                $result['confidence'] ?? null,
            ]);
            $result = $response->json();
        } catch (\Exception $e) {

            $result = null;
        }

        return view(
            'requests.review',
            compact(
                'requestData',
                'result'
            )
        );
    }

    public function approveRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | DSS Prediction
    |--------------------------------------------------------------------------
    */

        try {

            $response = Http::timeout(10)

                ->post("{$this->api}/predict-profit", [

                    'sales' =>
                    (float) $requestData->sales,

                    'quantity' =>
                    (int) $requestData->quantity,

                    'discount' =>
                    (float) $requestData->discount,

                    'shipping_days' =>
                    (int) $requestData->shipping_days,

                    'category' =>
                    $requestData->category,

                    'segment' =>
                    $requestData->segment,

                    'region' =>
                    $requestData->region,

                    'ship_mode' =>
                    $requestData->ship_mode,
                ]);

            $result = $response->json();
        } catch (\Exception $e) {

            $result = null;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Transaction
    |--------------------------------------------------------------------------
    */

        $requestData->update([

            'status' => 'approved',

            'prediction' =>

            $result['label_id']
                ?? null,

            'confidence' =>

            $result['prob_profitable']
                ?? null,

            'approved_by' => auth()->id(),

            'approved_at' => now(),
        ]);

        return redirect()

            ->route('requests.pending')

            ->with(
                'success',
                'Request berhasil di-approve.'
            );
    }

    public function rejectRequest($id)
    {
        $requestData = TransactionRequest::findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | DSS Prediction
    |--------------------------------------------------------------------------
    */

        try {

            $response = Http::timeout(10)

                ->post("{$this->api}/predict-profit", [

                    'sales' =>
                    (float) $requestData->sales,

                    'quantity' =>
                    (int) $requestData->quantity,

                    'discount' =>
                    (float) $requestData->discount,

                    'shipping_days' =>
                    (int) $requestData->shipping_days,

                    'category' =>
                    $requestData->category,

                    'segment' =>
                    $requestData->segment,

                    'region' =>
                    $requestData->region,

                    'ship_mode' =>
                    $requestData->ship_mode,
                ]);

            $result = $response->json();
        } catch (\Exception $e) {

            $result = null;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Transaction
    |--------------------------------------------------------------------------
    */

        $requestData->update([

            'status' => 'rejected',

            'prediction' =>

            $result['label_id']
                ?? null,

            'confidence' =>

            $result['prob_profitable']
                ?? null,

            'approved_by' => auth()->id(),

            'approved_at' => now(),
        ]);

        return redirect()

            ->route('requests.pending')

            ->with(
                'success',
                'Request berhasil di-reject.'
            );
    }

    public function transactionHistory()
    {
        $role = auth()->user()

            ->roles

            ->first()

            ?->name;

        /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

        $query = TransactionRequest::latest()

            ->whereIn('status', [
                'approved',
                'rejected'
            ])

            ->with([
                'requester',
                'approver'
            ]);

        /*
    |--------------------------------------------------------------------------
    | Role-Based Visibility
    |--------------------------------------------------------------------------
    */

        if ($role === 'procurement-director') {

            $query->where(
                'request_type',
                'procurement'
            );
        } elseif ($role === 'logistics-officer') {

            $query->where(
                'request_type',
                'shipment'
            );
        } elseif ($role === 'key-account-manager') {

            $query->where(
                'request_type',
                'contract'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Financial Controller
    |--------------------------------------------------------------------------
    */

        // financial-controller
        // sees all transactions

        $transactions = $query

            ->paginate(15);

        return view(
            'transactions.history',
            compact(
                'transactions'
            )
        );
    }

    private function getIntelligenceFeed($role)
    {
        $transactions = TransactionRequest::latest()

            ->whereIn('status', [
                'approved',
                'rejected'
            ])

            ->take(10)

            ->get();

        return $transactions->map(function ($trx) use ($role) {

            $message = null;

            /*
        |--------------------------------------------------------------------------
        | Logistics Officer
        |--------------------------------------------------------------------------
        */

            if (
                $role === 'logistics-officer'
                &&
                $trx->request_type === 'shipment'
            ) {

                $message =
                    $trx->status === 'approved'

                    ? "Shipment {$trx->ship_mode} approved. Prediksi profit {$trx->prediction}."

                    : "Shipment {$trx->ship_mode} ditolak karena risiko profit rendah.";
            }

            /*
        |--------------------------------------------------------------------------
        | Procurement Director
        |--------------------------------------------------------------------------
        */

            if (
                $role === 'procurement-director'
                &&
                $trx->request_type === 'procurement'
            ) {

                $message =
                    $trx->status === 'approved'

                    ? "Procurement {$trx->category} disetujui dengan confidence {$trx->confidence}."

                    : "Procurement {$trx->category} ditolak karena margin terlalu berisiko.";
            }

            /*
        |--------------------------------------------------------------------------
        | Key Account Manager
        |--------------------------------------------------------------------------
        */

            if (
                $role === 'key-account-manager'
                &&
                $trx->request_type === 'contract'
            ) {

                $message =
                    $trx->status === 'approved'

                    ? "Kontrak {$trx->segment} berhasil disetujui DSS."

                    : "Kontrak {$trx->segment} ditolak karena diskon melewati batas aman.";
            }

            if (!$message) {
                return null;
            }

            return [

                'title' =>
                $trx->title,

                'message' =>
                $message,

                'status' =>
                $trx->status,

                'prediction' =>
                $trx->prediction,

                'created_at' =>
                $trx->updated_at,
            ];
        })->filter();
    }
}
