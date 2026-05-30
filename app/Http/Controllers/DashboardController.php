<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('dashboard.index', [
            'user' => $user,
            'pendingLeerlingenCount' => User::query()
                ->where('role', User::ROLE_LEERLING)
                ->where('approval_status', 'pending')
                ->count(),
        ]);
    }
}
