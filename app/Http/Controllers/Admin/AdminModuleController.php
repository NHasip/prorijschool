<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use Symfony\Component\HttpFoundation\Response;

class AdminModuleController extends Controller
{
    public function dashboard(): Response
    {
        return app(StitchDesignController::class)->show('49e1b4a9d8d047dd93a57cb7ad15b837');
    }

    public function students(): Response
    {
        return app(StitchDesignController::class)->show('d8c5caf3191349c181d5ac0b56bde51f');
    }

    public function instructors(): Response
    {
        return app(StitchDesignController::class)->show('53228c93545c486793b47cb6c3437b68');
    }

    public function finance(): Response
    {
        return app(StitchDesignController::class)->show('3b33e08e659f42869b89a04b9909fe5b');
    }

    public function settings(): Response
    {
        return app(StitchDesignController::class)->show('8b40a88a6cd74d1a830860f3a45a3ad9');
    }
}
