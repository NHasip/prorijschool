<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureStudentProfiles();

        $query = Student::query()
            ->with(['user', 'instructorUser'])
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('users.role', User::ROLE_LEERLING)
            ->select('students.*');

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function ($builder) use ($search): void {
                $builder->whereHas('user', function ($userQuery) use ($search): void {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = (string) $request->input('status');
            $query->whereHas('user', fn ($userQuery) => $userQuery->where('approval_status', $status));
        }

        $students = $query
            ->orderByDesc('students.created_at')
            ->paginate(15)
            ->withQueryString();

        $instructors = User::query()
            ->where('role', User::ROLE_INSTRUCTEUR)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.students.index', [
            'students' => $students,
            'instructors' => $instructors,
            'filters' => [
                'q' => (string) $request->input('q', ''),
                'status' => (string) $request->input('status', ''),
            ],
        ]);
    }

    public function assignInstructor(Request $request, Student $student): RedirectResponse
    {
        if (! $student->user || ! $student->user->isRole(User::ROLE_LEERLING)) {
            abort(404);
        }

        $validated = $request->validate([
            'instructor_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $instructorId = $validated['instructor_user_id'] ?? null;

        if ($instructorId !== null) {
            $exists = User::query()
                ->whereKey($instructorId)
                ->where('role', User::ROLE_INSTRUCTEUR)
                ->exists();

            if (! $exists) {
                return back()->withErrors([
                    'instructor_user_id' => 'Geselecteerde gebruiker is geen instructeur.',
                ]);
            }
        }

        $student->forceFill([
            'instructor_user_id' => $instructorId,
        ])->save();

        return back()->with('status', 'Instructeur gekoppeld.');
    }

    public function updateStatus(Request $request, Student $student): RedirectResponse
    {
        if (! $student->user || ! $student->user->isRole(User::ROLE_LEERLING)) {
            abort(404);
        }

        $validated = $request->validate([
            'approval_status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $status = (string) $validated['approval_status'];
        $student->user->forceFill([
            'approval_status' => $status,
            'approved_at' => $status === 'approved' ? now() : null,
        ])->save();

        return back()->with('status', 'Leerlingstatus bijgewerkt.');
    }

    private function ensureStudentProfiles(): void
    {
        $learnerIds = User::query()
            ->where('role', User::ROLE_LEERLING)
            ->pluck('id');

        foreach ($learnerIds as $learnerId) {
            Student::query()->firstOrCreate(
                ['user_id' => $learnerId],
                ['student_number' => 'L'.str_pad((string) $learnerId, 5, '0', STR_PAD_LEFT)]
            );
        }
    }
}
