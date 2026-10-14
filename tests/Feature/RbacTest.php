<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_user_can_be_assigned_role_and_permissions(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('manage-users'));
        $this->assertTrue($user->hasPermissionTo('manage-appointments'));
    }

    public function test_staff_role_has_restricted_permissions(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $this->assertTrue($staff->hasRole('staff'));
        $this->assertTrue($staff->hasPermissionTo('manage-appointments'));
        $this->assertFalse($staff->hasPermissionTo('manage-users'));
    }

    public function test_role_middleware_blocks_unauthorized_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->getJson('/demo/admin-only');

        $response->assertStatus(403);
    }

    public function test_role_middleware_allows_admin_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson('/demo/admin-only');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Admin area accessed',
            ]);
    }
}
