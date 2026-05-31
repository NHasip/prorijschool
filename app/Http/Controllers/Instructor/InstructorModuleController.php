<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstructorModuleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '22f07a112aba424084edd5dca9c54fe6');
    }

    public function students(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '718be37108cb4803a072bc55aee33887');
    }

    public function planning(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '22f07a112aba424084edd5dca9c54fe6');
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
