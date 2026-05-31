<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_leerling_registration_starts_as_pending(): void
    {
        $response = $this->post('/registreren', [
            'name' => 'Nieuwe Leerling',
            'email' => 'leerling@example.com',
            'role' => User::ROLE_LEERLING,
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'email' => 'leerling@example.com',
            'role' => User::ROLE_LEERLING,
            'approval_status' => 'pending',
        ]);
    }

    public function test_admin_login_goes_to_dashboard_when_2fa_is_disabled(): void
    {
        config()->set('auth.portal.two_factor_enabled', false);

        $admin = User::factory()->create([
            'email' => 'admin2@example.com',
            'password' => 'Secret123!',
            'role' => User::ROLE_ADMIN,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'two_factor_enabled' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
        $this->assertNull(session('auth.2fa_user_id'));
    }
}
