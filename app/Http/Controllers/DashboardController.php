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
            ]
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
        return view('requests.create');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([

            'request_type' =>
            'required',

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
            $request->request_type,

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

        $requestData->update([

            'status' => 'approved',

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

        $requestData->update([

            'status' => 'rejected',

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
}
