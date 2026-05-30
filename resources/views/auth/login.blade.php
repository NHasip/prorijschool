@extends('layouts.app', ['title' => 'Inloggen'])

@section('content')
    <div class="card">
        <h1>Inloggen</h1>
        <p class="muted">Gebruik je account om in te loggen op het Pro Rijschool portaal.</p>

        <form method="post" action="{{ route('login.attempt') }}">
            @csrf
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Wachtwoord</label>
            <input id="password" type="password" name="password" required>

            <label class="row" style="margin-top: 12px; font-weight: 500;">
                <input type="checkbox" name="remember" style="width: auto;">
                Onthoud mij
            </label>

            <div class="row" style="margin-top: 14px;">
                <button type="submit">Inloggen</button>
                <a class="btn btn-muted" href="{{ route('register') }}">Registreren</a>
            </div>
        </form>
    </div>
@endsection
