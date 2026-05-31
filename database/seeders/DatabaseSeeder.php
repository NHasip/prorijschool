<?php

namespace Database\Seeders;

use App\Models\LessonPackage;
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
    }
}
