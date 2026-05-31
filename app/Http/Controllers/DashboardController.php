<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $target = match ($user->role) {
            User::ROLE_ADMIN, User::ROLE_BEHEERDER => 'admin.dashboard',
            User::ROLE_INSTRUCTEUR => 'instructor.dashboard',
            User::ROLE_LEERLING => 'learner.dashboard',
            default => 'login',
        };

        return redirect()->route($target);
    }
}
