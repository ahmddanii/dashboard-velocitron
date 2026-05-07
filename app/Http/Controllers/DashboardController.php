<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    // Base URL Flask API
    private string $api = 'http://localhost:5000/api';

    // ── Dashboard Utama ───────────────────────────────────────
    public function index()
    {
        try {
            $summary  = Http::timeout(5)->get("{$this->api}/summary")->json();
            $yearly   = Http::timeout(5)->get("{$this->api}/yearly-trend")->json();
            $monthly  = Http::timeout(5)->get("{$this->api}/monthly-trend")->json();
            $category = Http::timeout(5)->get("{$this->api}/profit-by-category")->json();
            $region   = Http::timeout(5)->get("{$this->api}/sales-by-region")->json();
            $segment  = Http::timeout(5)->get("{$this->api}/sales-by-segment")->json();
            $products = Http::timeout(5)->get("{$this->api}/top-products")->json();
        } catch (\Exception $e) {
            // Jika Flask tidak jalan, tampilkan pesan error
            return view('dashboard.index', ['apiError' => true]);
        }

        return view('dashboard.index', compact(
            'summary',
            'yearly',
            'monthly',
            'category',
            'region',
            'segment',
            'products'
        ));
    }

    // ── Halaman DSS Prediksi ──────────────────────────────────
    public function dss()
    {
        return view('dashboard.dss');
    }

    // ── Proses Form DSS ───────────────────────────────────────
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
            return back()->withErrors(['api' => 'Flask API tidak dapat dihubungi. Pastikan python app.py sudah berjalan.']);
        }

        return view('dashboard.dss', [
            'result' => $result,
            'input'  => $request->all(),
        ]);
    }
}
