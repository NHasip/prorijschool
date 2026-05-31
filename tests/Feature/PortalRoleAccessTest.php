<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_modules(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'approval_status' => 'approved',
        ]);

        $this->actingAs($admin)
            ->withSession(['auth.2fa_passed' => true])
            ->get('/admin/leerlingen')
            ->assertOk();

        $this->actingAs($admin)
            ->withSession(['auth.2fa_passed' => true])
            ->get('/admin/financien')
            ->assertOk();
    }

    public function test_instructor_cannot_access_admin_modules(): void
    {
        $instructor = User::factory()->create([
            'role' => User::ROLE_INSTRUCTEUR,
            'approval_status' => 'approved',
        ]);

        $this->actingAs($instructor)
            ->withSession(['auth.2fa_passed' => true])
            ->get('/admin/leerlingen')
            ->assertForbidden();
    }
}

