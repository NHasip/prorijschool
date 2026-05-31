<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonPlanningWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_plan_lesson_for_assigned_student(): void
    {
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTEUR, 'approval_status' => 'approved']);
        $learner = User::factory()->create(['role' => User::ROLE_LEERLING, 'approval_status' => 'approved']);
        $student = Student::query()->create([
            'user_id' => $learner->id,
            'instructor_user_id' => $instructor->id,
            'student_number' => 'L20001',
        ]);

        $this->actingAs($instructor)
            ->withSession(['auth.2fa_passed' => true])
            ->post(route('instructor.planning.store'), [
                'student_id' => $student->id,
                'starts_at' => now()->addDay()->format('Y-m-d H:i:s'),
                'ends_at' => now()->addDay()->addHour()->format('Y-m-d H:i:s'),
                'location' => 'Utrecht',
                'lesson_type' => 'praktijkles',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('lessons', 1);
        $this->assertDatabaseHas('lessons', [
            'student_id' => $student->id,
            'instructor_user_id' => $instructor->id,
        ]);
    }

    public function test_instructor_cannot_plan_lesson_for_other_instructor_student(): void
    {
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTEUR, 'approval_status' => 'approved']);
        $otherInstructor = User::factory()->create(['role' => User::ROLE_INSTRUCTEUR, 'approval_status' => 'approved']);
        $learner = User::factory()->create(['role' => User::ROLE_LEERLING, 'approval_status' => 'approved']);
        $student = Student::query()->create([
            'user_id' => $learner->id,
            'instructor_user_id' => $otherInstructor->id,
            'student_number' => 'L20002',
        ]);

        $this->actingAs($instructor)
            ->withSession(['auth.2fa_passed' => true])
            ->post(route('instructor.planning.store'), [
                'student_id' => $student->id,
                'starts_at' => now()->addDay()->format('Y-m-d H:i:s'),
                'ends_at' => now()->addDay()->addHour()->format('Y-m-d H:i:s'),
                'location' => 'Utrecht',
                'lesson_type' => 'praktijkles',
            ])
            ->assertSessionHasErrors('student_id');

        $this->assertDatabaseCount('lessons', 0);
    }
}

