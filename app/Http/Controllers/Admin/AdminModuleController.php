<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminModuleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '49e1b4a9d8d047dd93a57cb7ad15b837');
    }

    public function students(Request $request): View
    {
        return app(StudentManagementController::class)->index($request);
    }

    public function instructors(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '53228c93545c486793b47cb6c3437b68');
    }

    public function finance(Request $request): View
    {
        return app(FinanceManagementController::class)->index();
    }

    public function settings(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '8b40a88a6cd74d1a830860f3a45a3ad9');
    }
}
