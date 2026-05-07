<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        ) + ['role' => 'head-analytics']);
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
            'role'    => 'financial-controller',
            'monthly' => [],
            'segment' => [],
            'products' => [],
        ]);
    }

    // ── Chief Logistics Officer ───────────────────────────────
    // Fokus: Ship Mode, Shipping Days, distribusi region
    private function dashboardLogistics()
    {
        $summary  = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];
        $region   = Http::timeout(5)->get("{$this->api}/sales-by-region")->json() ?? [];
        $yearly   = Http::timeout(5)->get("{$this->api}/yearly-trend")->json() ?? [];

        return view('dashboard.index', compact(
            'summary',
            'region',
            'yearly'
        ) + [
            'role'     => 'logistics-officer',
            'monthly'  => [],
            'category' => [],
            'segment'  => [],
            'products' => [],
        ]);
    }

    // ── Director of Strategic Procurement ────────────────────
    // Fokus: Kategori Technology & Furniture
    private function dashboardProcurement()
    {
        $summary  = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];
        $category = Http::timeout(5)->get("{$this->api}/profit-by-category")->json() ?? [];
        $products = Http::timeout(5)->get("{$this->api}/top-products")->json() ?? [];

        // Filter hanya Technology & Furniture
        $category = array_filter(
            $category,
            fn($c) =>
            in_array($c['category'], ['Technology', 'Furniture'])
        );

        return view('dashboard.index', compact(
            'summary',
            'category',
            'products'
        ) + [
            'role'    => 'procurement-director',
            'monthly' => [],
            'yearly' => [],
            'region'  => [],
            'segment' => [],
        ]);
    }

    // ── Key Account Manager ───────────────────────────────────
    // Fokus: Segmen Corporate & Home Office
    private function dashboardKAM()
    {
        $summary  = Http::timeout(5)->get("{$this->api}/summary")->json() ?? [];
        $segment  = Http::timeout(5)->get("{$this->api}/sales-by-segment")->json() ?? [];
        $region   = Http::timeout(5)->get("{$this->api}/sales-by-region")->json() ?? [];

        // Filter hanya Corporate & Home Office
        $segment = array_filter(
            $segment,
            fn($s) =>
            in_array($s['segment'], ['Corporate', 'Home Office'])
        );

        return view('dashboard.index', compact(
            'summary',
            'segment',
            'region'
        ) + [
            'role'     => 'key-account-manager',
            'monthly'  => [],
            'yearly' => [],
            'category' => [],
            'products' => [],
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

            $result = $response->json();
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Flask API tidak dapat dihubungi.']);
        }

        return view('dashboard.dss', [
            'result' => $result,
            'input'  => $request->all(),
        ]);
    }
}
