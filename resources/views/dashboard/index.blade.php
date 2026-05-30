@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="card">
        <h1>Dashboard</h1>
        <p>Welkom, <strong>{{ $user->name }}</strong>.</p>
        <p class="muted">Rol: {{ ucfirst($user->role) }}</p>
        @if($user->isRole('leerling'))
            <p class="muted">2FA optioneel: {{ $user->two_factor_enabled ? 'Ingeschakeld' : 'Uitgeschakeld' }}</p>
        @endif

        <div class="row" style="margin-top: 12px;">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-muted">Uitloggen</button>
            </form>

            @if($user->isRole('leerling'))
                <form method="post" action="{{ route('2fa.toggle') }}">
                    @csrf
                    <button type="submit">{{ $user->two_factor_enabled ? '2FA uitschakelen' : '2FA inschakelen' }}</button>
                </form>
            @endif

            <a class="btn" href="{{ route('stitch.index') }}">Stitch Design Baseline</a>
        </div>
    </div>

    @if($user->isRole('admin', 'beheerder'))
        <div class="card">
            <h2>Beheer</h2>
            <p>Wachtende leerlingen: <strong>{{ $pendingLeerlingenCount }}</strong></p>
            <a class="btn" href="{{ route('admin.approvals.index') }}">Leerlingen goedkeuren</a>
        </div>
    @endif
@endsection
