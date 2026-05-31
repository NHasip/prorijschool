<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class InstructorManagementController extends Controller
{
    public function index(): View
    {
        $instructors = User::query()
            ->withCount('instructedStudents')
            ->where('role', User::ROLE_INSTRUCTEUR)
            ->orderBy('name')
            ->paginate(12);

        return view('admin.instructors.index', [
            'instructors' => $instructors,
        ]);
    }
}

