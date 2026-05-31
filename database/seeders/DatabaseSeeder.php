<?php

namespace Database\Seeders;

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
    }
}
