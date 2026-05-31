<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminModuleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '49e1b4a9d8d047dd93a57cb7ad15b837');
    }

    public function students(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, 'd8c5caf3191349c181d5ac0b56bde51f');
    }

    public function instructors(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '53228c93545c486793b47cb6c3437b68');
    }

    public function finance(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '3b33e08e659f42869b89a04b9909fe5b');
    }

    public function settings(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '8b40a88a6cd74d1a830860f3a45a3ad9');
    }
}
