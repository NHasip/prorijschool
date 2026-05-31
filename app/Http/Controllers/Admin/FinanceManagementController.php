<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\LessonPackage;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FinanceManagementController extends Controller
{
    public function index(): View
    {
        $payments = Payment::query()
            ->with(['student.user', 'lessonPackage', 'invoice'])
            ->latest()
            ->paginate(15);

        $totals = [
            'openstaand_cents' => Payment::query()
                ->whereIn('status', ['pending', 'open'])
                ->sum('amount_cents'),
            'betaald_cents' => Payment::query()
                ->where('status', 'paid')
                ->sum('amount_cents'),
            'facturen_count' => Invoice::query()->count(),
        ];

        $students = Student::query()
            ->with('user:id,name')
            ->orderBy('student_number')
            ->get(['id', 'user_id', 'student_number']);

        $packages = LessonPackage::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.finance.index', [
            'payments' => $payments,
            'totals' => $totals,
            'students' => $students,
            'packages' => $packages,
        ]);
    }

    public function createInvoice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'lesson_package_id' => ['nullable', 'exists:lesson_packages,id'],
            'subtotal_eur' => ['required', 'numeric', 'min:0.01'],
        ]);

        $student = Student::query()->with('user')->findOrFail((int) $validated['student_id']);
        if (! $student->user || ! $student->user->isRole(User::ROLE_LEERLING)) {
            abort(404);
        }

        $subtotalCents = (int) round(((float) $validated['subtotal_eur']) * 100);
        $vatPercent = 21;
        $vatCents = (int) round($subtotalCents * ($vatPercent / 100));
        $totalCents = $subtotalCents + $vatCents;

        $payment = Payment::query()->create([
            'student_id' => (int) $validated['student_id'],
            'lesson_package_id' => $validated['lesson_package_id'] ?? null,
            'provider' => 'manual',
            'provider_payment_id' => 'manual_'.Str::ulid(),
            'status' => 'open',
            'amount_cents' => $subtotalCents,
            'meta' => [
                'created_by' => 'admin',
                'btw_percent' => $vatPercent,
            ],
        ]);

        Invoice::query()->create([
            'payment_id' => $payment->id,
            'invoice_number' => 'INV-'.now()->format('Ymd').'-'.str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT),
            'subtotal_cents' => $subtotalCents,
            'vat_percent' => $vatPercent,
            'vat_cents' => $vatCents,
            'total_cents' => $totalCents,
            'issued_at' => now(),
        ]);

        return back()->with('status', 'Factuur aangemaakt met 21% BTW.');
    }

    public function updatePaymentStatus(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,pending,paid,failed,cancelled'],
        ]);

        $status = (string) $validated['status'];
        $payment->forceFill([
            'status' => $status,
            'paid_at' => $status === 'paid' ? now() : null,
        ])->save();

        return back()->with('status', 'Betalingsstatus bijgewerkt.');
    }
}
