<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function index(): View
    {
        $pendingUsers = User::query()
            ->where('role', User::ROLE_LEERLING)
            ->where('approval_status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('admin.user-approvals.index', [
            'pendingUsers' => $pendingUsers,
        ]);
    }

    public function approve(User $user): RedirectResponse
    {
        if (! $user->isRole(User::ROLE_LEERLING)) {
            return back();
        }

        $user->forceFill([
            'approval_status' => 'approved',
            'approved_at' => now(),
        ])->save();

        return back()->with('status', "{$user->name} is goedgekeurd.");
    }

    public function reject(User $user): RedirectResponse
    {
        if (! $user->isRole(User::ROLE_LEERLING)) {
            return back();
        }

        $user->forceFill([
            'approval_status' => 'rejected',
            'approved_at' => null,
        ])->save();

        return back()->with('status', "{$user->name} is afgewezen.");
    }
}
