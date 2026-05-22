<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TransactionRequest;
use Illuminate\Support\Facades\Http;

class TransactionRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_transaction_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('operator');

        $requestData = [
            'request_type' => 'procurement',
            'title' => 'Test Procurement',
            'description' => 'Test Description',
            'sales' => 1000,
            'quantity' => 10,
            'discount' => 0.1,
            'shipping_days' => 5,
            'category' => 'Technology',
            'segment' => 'Corporate',
            'region' => 'West',
            'ship_mode' => 'Standard Class',
        ];

        $response = $this->actingAs($user)->post(route('requests.store'), $requestData);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('transaction_requests', [
            'title' => 'Test Procurement',
            'requester_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_head_analytics_can_approve_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $transactionRequest = TransactionRequest::create([
            'requester_id' => $operator->id,
            'request_type' => 'procurement',
            'title' => 'Test Request',
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

        Http::fake([
            '*' => Http::response([
                'label_id' => 'Profitable',
                'prob_profitable' => 0.95
            ], 200)
        ]);

        $response = $this->actingAs($admin)->post(route('requests.approve', $transactionRequest->id));

        $response->assertRedirect(route('requests.pending'));
        $this->assertDatabaseHas('transaction_requests', [
            'id' => $transactionRequest->id,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'prediction' => 'Profitable',
            'confidence' => 0.95,
        ]);
    }

    public function test_head_analytics_can_reject_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $transactionRequest = TransactionRequest::create([
            'requester_id' => $operator->id,
            'request_type' => 'procurement',
            'title' => 'Test Request',
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

        Http::fake([
            '*' => Http::response([
                'label_id' => 'Loss',
                'prob_profitable' => 0.20
            ], 200)
        ]);

        $response = $this->actingAs($admin)->post(route('requests.reject', $transactionRequest->id));

        $response->assertRedirect(route('requests.pending'));
        $this->assertDatabaseHas('transaction_requests', [
            'id' => $transactionRequest->id,
            'status' => 'rejected',
            'approved_by' => $admin->id,
        ]);
    }

    public function test_user_can_edit_own_pending_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('procurement-director');

        $transactionRequest = TransactionRequest::create([
            'requester_id' => $user->id,
            'request_type' => 'procurement',
            'title' => 'Test Request',
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

        $response = $this->actingAs($user)->get(route('requests.edit', $transactionRequest->id));

        $response->assertStatus(200);
        $response->assertViewIs('requests.edit');
        $response->assertSee('Test Request');
    }

    public function test_user_cannot_edit_other_user_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUser->assignRole('procurement-director');

        $transactionRequest = TransactionRequest::create([
            'requester_id' => $owner->id,
            'request_type' => 'procurement',
            'title' => 'Test Request',
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

        $response = $this->actingAs($otherUser)->get(route('requests.edit', $transactionRequest->id));

        $response->assertStatus(403);
    }

    public function test_user_can_update_own_pending_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('procurement-director');

        $transactionRequest = TransactionRequest::create([
            'requester_id' => $user->id,
            'request_type' => 'procurement',
            'title' => 'Old Title',
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

        $updateData = [
            'title' => 'Updated Title',
            'sales' => 2000,
            'quantity' => 20,
            'discount' => 0.2,
            'shipping_days' => 3,
            'category' => 'Furniture',
            'segment' => 'Consumer',
            'region' => 'East',
            'ship_mode' => 'First Class',
        ];

        $response = $this->actingAs($user)->put(route('requests.update', $transactionRequest->id), $updateData);

        $response->assertRedirect(route('transactions.history'));
        $this->assertDatabaseHas('transaction_requests', [
            'id' => $transactionRequest->id,
            'title' => 'Updated Title',
            'sales' => 2000,
            'quantity' => 20,
        ]);
    }

    public function test_user_can_cancel_own_pending_request()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('procurement-director');

        $transactionRequest = TransactionRequest::create([
            'requester_id' => $user->id,
            'request_type' => 'procurement',
            'title' => 'Test Request',
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

        $response = $this->actingAs($user)->delete(route('requests.cancel', $transactionRequest->id));

        $response->assertRedirect(route('transactions.history'));
        $this->assertDatabaseMissing('transaction_requests', [
            'id' => $transactionRequest->id,
        ]);
    }

    public function test_validation_errors_when_creating_request_with_invalid_data()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('procurement-director');

        $invalidData = [
            'title' => '', // Required
            'sales' => -100, // min:0
            'quantity' => 0, // min:1
            'discount' => 1.5, // max:0.8
        ];

        $response = $this->actingAs($user)->post(route('requests.store'), $invalidData);

        $response->assertSessionHasErrors(['title', 'sales', 'quantity', 'discount']);
    }
}
