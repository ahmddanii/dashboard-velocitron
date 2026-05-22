<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        // Fake external API response
        Http::fake([
            '*' => Http::response([], 200)
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    public function test_dss_can_be_accessed_by_head_analytics()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('head-analytics');

        $response = $this->actingAs($user)->get('/dashboard/dss');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.dss');
    }

    public function test_dss_cannot_be_accessed_by_operator()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('operator');

        $response = $this->actingAs($user)->get('/dashboard/dss');

        $response->assertStatus(403);
    }

    // ==========================================
    // Role-Based Dashboard View Tests
    // ==========================================

    public function test_financial_controller_can_access_dashboard()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'financial-controller', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('financial-controller');

        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    public function test_logistics_officer_can_access_dashboard()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'logistics-officer', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('logistics-officer');

        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    public function test_procurement_director_can_access_dashboard()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('procurement-director');

        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    public function test_key_account_manager_can_access_dashboard()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'key-account-manager', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('key-account-manager');

        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    public function test_financial_controller_can_access_dss()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'financial-controller', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('financial-controller');

        $response = $this->actingAs($user)->get('/dashboard/dss');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.dss');
    }

    // ==========================================
    // Review Request Tests
    // ==========================================

    public function test_user_can_view_review_page()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $request = \App\Models\TransactionRequest::create([
            'requester_id' => $admin->id,
            'request_type' => 'procurement',
            'title' => 'Review Test Request',
            'sales' => 1000,
            'quantity' => 10,
            'discount' => 0.1,
            'shipping_days' => 5,
            'category' => 'Technology',
            'segment' => 'Corporate',
            'region' => 'West',
            'ship_mode' => 'Standard Class',
            'status' => 'pending',
        ]);

        Http::fake(['*' => Http::response([
            'prediction' => 1,
            'label_id' => 'Profitable',
            'prob_profitable' => 85.0,
            'prob_loss' => 15.0,
            'confidence' => '85%',
        ], 200)]);

        $response = $this->actingAs($admin)->get(route('requests.review', $request->id));

        $response->assertStatus(200);
        $response->assertViewIs('requests.review');
    }

    public function test_user_can_view_pending_requests()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        \App\Models\TransactionRequest::create([
            'requester_id' => $admin->id,
            'request_type' => 'procurement',
            'title' => 'Pending Test',
            'sales' => 500,
            'quantity' => 5,
            'discount' => 0,
            'shipping_days' => 3,
            'category' => 'Technology',
            'segment' => 'Corporate',
            'region' => 'West',
            'ship_mode' => 'Standard Class',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('requests.pending'));

        $response->assertStatus(200);
        $response->assertSee('Pending Test');
    }

    public function test_guest_cannot_access_pending_requests()
    {
        $response = $this->get(route('requests.pending'));
        $response->assertRedirect('/login');
    }
}

