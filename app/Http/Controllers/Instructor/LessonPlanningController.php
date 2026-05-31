<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LessonPlanningController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $assignedStudents = Student::query()
            ->with('user:id,name')
            ->where('instructor_user_id', $user->id)
            ->orderBy('student_number')
            ->get(['id', 'user_id', 'student_number']);

        $lessons = Lesson::query()
            ->with(['student.user'])
            ->where('instructor_user_id', $user->id)
            ->orderBy('starts_at')
            ->paginate(20);

        return view('instructor.planning.index', [
            'assignedStudents' => $assignedStudents,
            'lessons' => $lessons,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'lesson_type' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $student = Student::query()->findOrFail((int) $validated['student_id']);
        if ($student->instructor_user_id !== $user->id) {
            throw ValidationException::withMessages([
                'student_id' => 'Je kunt alleen lessen plannen voor eigen leerlingen.',
            ]);
        }

        Lesson::query()->create([
            'student_id' => $student->id,
            'instructor_user_id' => $user->id,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'location' => $validated['location'] ?? null,
            'lesson_type' => $validated['lesson_type'],
            'status' => 'planned',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('status', 'Les ingepland.');
    }

    public function updateStatus(Request $request, Lesson $lesson): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($lesson->instructor_user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:planned,completed,cancelled'],
        ]);

        $lesson->forceFill([
            'status' => (string) $validated['status'],
        ])->save();

        return back()->with('status', 'Lesstatus bijgewerkt.');
    }
}

