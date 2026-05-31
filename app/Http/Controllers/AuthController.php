<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_LEERLING,
            'approval_status' => 'pending',
            'approved_at' => null,
            'two_factor_enabled' => false,
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Registratie ontvangen. Je account wacht op goedkeuring door de rijschool.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'De inloggegevens zijn onjuist.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        /** @var User $user */
        $user = Auth::user();

        if (! $user->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Je account is nog niet goedgekeurd.',
            ]);
        }

        if ($user->requiresTwoFactor()) {
            $code = $user->generateTwoFactorCode();

            Mail::raw(
                "Je Pro Rijschool verificatiecode is: {$code}. Deze code verloopt over 10 minuten.",
                fn ($message) => $message
                    ->to($user->email)
                    ->subject('Pro Rijschool - Verificatiecode')
            );

            Auth::logout();
            $request->session()->put('auth.2fa_user_id', $user->id);
            $request->session()->put('auth.remember', $request->boolean('remember'));

            return redirect()->route('2fa.challenge')->with('status', 'Verificatiecode verzonden naar je e-mail.');
        }

        $request->session()->put('auth.2fa_passed', true);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
