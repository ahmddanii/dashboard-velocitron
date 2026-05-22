<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_head_analytics_can_view_users_list()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $response = $this->actingAs($admin)->get('/users');

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_non_head_analytics_cannot_view_users_list()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('operator');

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(403);
    }

    public function test_head_analytics_can_create_user()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $userData = [
            'name' => 'New Operator',
            'email' => 'operator@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
        ];

        $response = $this->actingAs($admin)->post('/users', $userData);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'operator@test.com',
        ]);
        $createdUser = User::where('email', 'operator@test.com')->first();
        $this->assertTrue($createdUser->hasRole('operator'));
    }

    public function test_head_analytics_can_delete_user()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $userToDelete = User::factory()->create();

        $response = $this->actingAs($admin)->from('/users')->delete('/users/' . $userToDelete->id);

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }
}
