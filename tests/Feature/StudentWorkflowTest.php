<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_instructor_and_update_status(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'approval_status' => 'approved']);
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTEUR, 'approval_status' => 'approved']);
        $learner = User::factory()->create(['role' => User::ROLE_LEERLING, 'approval_status' => 'pending', 'approved_at' => null]);
        $student = Student::query()->create([
            'user_id' => $learner->id,
            'student_number' => 'L10001',
        ]);

        $this->actingAs($admin)
            ->withSession(['auth.2fa_passed' => true])
            ->post(route('admin.students.assign-instructor', $student), [
                'instructor_user_id' => $instructor->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'instructor_user_id' => $instructor->id,
        ]);

        $this->actingAs($admin)
            ->withSession(['auth.2fa_passed' => true])
            ->post(route('admin.students.update-status', $student), [
                'approval_status' => 'approved',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $learner->id,
            'approval_status' => 'approved',
        ]);
    }
}

