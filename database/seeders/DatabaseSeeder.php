<?php

namespace Database\Seeders;

use App\Models\LessonPackage;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $legacyAdmin = User::query()->where('email', 'admin@prorijschool.nl')->first();
        if ($legacyAdmin) {
            $legacyAdmin->forceFill([
                'email' => 'necip@necmar.nl',
            ])->save();
        }

        User::updateOrCreate([
            'email' => 'necip@necmar.nl',
        ], [
            'name' => 'Portal Admin',
            'password' => 'Admin123!secure',
            'role' => User::ROLE_ADMIN,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'two_factor_enabled' => true,
        ]);

        LessonPackage::updateOrCreate(['slug' => 'brons'], [
            'name' => 'Brons',
            'package_type' => 'rijlespakket',
            'lesson_count' => 20,
            'lesson_minutes' => 60,
            'price_cents' => 140000,
            'vat_percent' => 21,
            'is_active' => true,
        ]);

        LessonPackage::updateOrCreate(['slug' => 'goud'], [
            'name' => 'Goud',
            'package_type' => 'rijlespakket',
            'lesson_count' => 30,
            'lesson_minutes' => 60,
            'price_cents' => 205000,
            'vat_percent' => 21,
            'is_active' => true,
        ]);

        LessonPackage::updateOrCreate(['slug' => 'diamant'], [
            'name' => 'Diamant',
            'package_type' => 'rijlespakket',
            'lesson_count' => 40,
            'lesson_minutes' => 60,
            'price_cents' => 265000,
            'vat_percent' => 21,
            'is_active' => true,
        ]);

        LessonPackage::updateOrCreate(['slug' => 'losse-les-60'], [
            'name' => 'Losse les (60 min)',
            'package_type' => 'losse_les',
            'lesson_count' => 1,
            'lesson_minutes' => 60,
            'price_cents' => 7000,
            'vat_percent' => 21,
            'is_active' => true,
        ]);

        $instructor = User::updateOrCreate([
            'email' => 'instructeur@prorijschool.nl',
        ], [
            'name' => 'Mark Instructeur',
            'password' => 'Admin123!secure',
            'role' => User::ROLE_INSTRUCTEUR,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'two_factor_enabled' => false,
        ]);

        Instructor::updateOrCreate([
            'user_id' => $instructor->id,
        ], [
            'employee_code' => 'INS-001',
            'is_active' => true,
        ]);

        $learner = User::updateOrCreate([
            'email' => 'leerling@prorijschool.nl',
        ], [
            'name' => 'Sanne Leerling',
            'password' => 'Admin123!secure',
            'role' => User::ROLE_LEERLING,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'two_factor_enabled' => false,
        ]);

        $student = Student::updateOrCreate([
            'user_id' => $learner->id,
        ], [
            'instructor_user_id' => $instructor->id,
            'student_number' => 'L00001',
            'lesson_balance_minutes' => 600,
        ]);

        $package = LessonPackage::query()->where('slug', 'brons')->first();
        if ($package) {
            $payment = Payment::updateOrCreate([
                'provider_payment_id' => 'seed-payment-1',
            ], [
                'student_id' => $student->id,
                'lesson_package_id' => $package->id,
                'provider' => 'manual',
                'status' => 'open',
                'amount_cents' => 120000,
            ]);

            Invoice::updateOrCreate([
                'payment_id' => $payment->id,
            ], [
                'invoice_number' => 'INV-'.now()->format('Ymd').'-SEED1',
                'subtotal_cents' => 120000,
                'vat_percent' => 21,
                'vat_cents' => 25200,
                'total_cents' => 145200,
                'issued_at' => now(),
            ]);
        }

        Lesson::updateOrCreate([
            'student_id' => $student->id,
            'starts_at' => now()->addDay()->startOfHour(),
        ], [
            'instructor_user_id' => $instructor->id,
            'ends_at' => now()->addDay()->startOfHour()->addHour(),
            'location' => 'Utrecht Centrum',
            'lesson_type' => 'praktijkles',
            'status' => 'planned',
        ]);
    }
}
