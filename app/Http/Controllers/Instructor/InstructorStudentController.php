<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorStudentController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $students = Student::query()
            ->with(['user', 'lessons' => fn ($query) => $query->latest()->limit(1)])
            ->where('instructor_user_id', $user->id)
            ->orderBy('student_number')
            ->paginate(20);

        return view('instructor.students.index', [
            'students' => $students,
        ]);
    }
}

