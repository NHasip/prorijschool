<?php

namespace Tests\Feature;

use App\Models\LessonPackage;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_invoice_with_21_percent_vat(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'approval_status' => 'approved']);
        $learner = User::factory()->create(['role' => User::ROLE_LEERLING, 'approval_status' => 'approved']);
        $student = Student::query()->create(['user_id' => $learner->id, 'student_number' => 'L30001']);
        $package = LessonPackage::query()->create([
            'name' => 'Testpakket',
            'slug' => 'testpakket',
            'package_type' => 'rijlespakket',
            'lesson_count' => 10,
            'lesson_minutes' => 60,
            'price_cents' => 100000,
            'vat_percent' => 21,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->withSession(['auth.2fa_passed' => true])
            ->post(route('admin.finance.create-invoice'), [
                'student_id' => $student->id,
                'lesson_package_id' => $package->id,
                'subtotal_eur' => 1000,
            ])
            ->assertRedirect();

        $payment = Payment::query()->firstOrFail();
        $invoice = $payment->invoice()->firstOrFail();

        $this->assertSame(100000, $invoice->subtotal_cents);
        $this->assertSame(21, $invoice->vat_percent);
        $this->assertSame(21000, $invoice->vat_cents);
        $this->assertSame(121000, $invoice->total_cents);
    }

    public function test_admin_can_update_payment_status(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'approval_status' => 'approved']);
        $learner = User::factory()->create(['role' => User::ROLE_LEERLING, 'approval_status' => 'approved']);
        $student = Student::query()->create(['user_id' => $learner->id, 'student_number' => 'L30002']);
        $payment = Payment::query()->create([
            'student_id' => $student->id,
            'provider' => 'manual',
            'status' => 'open',
            'amount_cents' => 50000,
        ]);

        $this->actingAs($admin)
            ->withSession(['auth.2fa_passed' => true])
            ->post(route('admin.finance.update-payment-status', $payment), [
                'status' => 'paid',
            ])
            ->assertRedirect();

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertNotNull($payment->paid_at);
    }
}

