<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $screenId = match ($user->role) {
            User::ROLE_ADMIN, User::ROLE_BEHEERDER => '49e1b4a9d8d047dd93a57cb7ad15b837',
            User::ROLE_INSTRUCTEUR => '22f07a112aba424084edd5dca9c54fe6',
            default => '0413e936266e41c493dcc562d3032b00',
        };

        return app(StitchDesignController::class)->show($screenId);
    }
}
