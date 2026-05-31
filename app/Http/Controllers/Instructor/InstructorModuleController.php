<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class InstructorModuleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '22f07a112aba424084edd5dca9c54fe6');
    }

    public function students(Request $request): View
    {
        return app(InstructorStudentController::class)->index($request);
    }

    public function planning(Request $request): View
    {
        return app(LessonPlanningController::class)->index($request);
    }

    public function risModules(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '7249dced71624587b4fb3348a5a3fedf');
    }

    public function settings(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '22f07a112aba424084edd5dca9c54fe6');
    }
}
