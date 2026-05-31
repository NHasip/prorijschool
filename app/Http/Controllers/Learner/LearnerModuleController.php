<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LearnerModuleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '0413e936266e41c493dcc562d3032b00');
    }

    public function planning(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = Student::query()->where('user_id', $user->id)->first();

        $lessons = $student?->lessons()
            ->with('instructorUser:id,name')
            ->orderBy('starts_at')
            ->paginate(20);

        return view('learner.planning.index', [
            'student' => $student,
            'lessons' => $lessons,
        ]);
    }

    public function progress(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, 'b55a461e46714f29bc9d56086b0b9a28');
    }

    public function progressDetail(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '3fa4a71383a848abb40a3381435b36b9');
    }

    public function invoices(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = Student::query()->where('user_id', $user->id)->first();

        $payments = $student?->payments()
            ->with(['lessonPackage', 'invoice'])
            ->latest()
            ->paginate(20);

        return view('learner.invoices.index', [
            'student' => $student,
            'payments' => $payments,
        ]);
    }

    public function theory(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, 'b213ebbaf7ba43129d1557010886d377');
    }
}
