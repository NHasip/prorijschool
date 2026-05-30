<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    public function test_admin_login_redirects_to_two_factor_challenge(): void
    {
        Mail::fake();

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

        $response->assertRedirect('/2fa');
        $this->assertGuest();
        $this->assertEquals($admin->id, session('auth.2fa_user_id'));
    }
}
