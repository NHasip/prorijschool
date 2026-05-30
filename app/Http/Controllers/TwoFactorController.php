<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function showChallenge(Request $request): View|RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.two-factor', ['email' => $user->email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (
            ! $user->two_factor_code ||
            ! $user->two_factor_expires_at ||
            now()->greaterThan($user->two_factor_expires_at) ||
            ! Hash::check($validated['code'], $user->two_factor_code)
        ) {
            return back()->withErrors(['code' => 'Ongeldige of verlopen verificatiecode.']);
        }

        $remember = $request->session()->pull('auth.remember', false);
        $request->session()->forget('auth.2fa_user_id');
        $user->clearTwoFactorCode();

        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('auth.2fa_passed', true);

        return redirect()->intended(route('dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        $code = $user->generateTwoFactorCode();

        Mail::raw(
            "Je nieuwe Pro Rijschool verificatiecode is: {$code}. Deze code verloopt over 10 minuten.",
            fn ($message) => $message
                ->to($user->email)
                ->subject('Pro Rijschool - Nieuwe verificatiecode')
        );

        return back()->with('status', 'Nieuwe code verzonden.');
    }

    public function toggle(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user || ! $user->isRole(User::ROLE_LEERLING)) {
            return back();
        }

        $user->forceFill([
            'two_factor_enabled' => ! $user->two_factor_enabled,
        ])->save();

        return back()->with('status', $user->two_factor_enabled
            ? '2FA is ingeschakeld.'
            : '2FA is uitgeschakeld.');
    }

    private function pendingUser(Request $request): ?User
    {
        $userId = $request->session()->get('auth.2fa_user_id');

        if (! $userId) {
            return null;
        }

        return User::query()->find($userId);
    }
}
