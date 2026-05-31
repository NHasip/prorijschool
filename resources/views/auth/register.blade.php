@extends('layouts.app', ['title' => 'Registreren'])

@section('content')
    <div class="card">
        <h1>Registreren</h1>
        <p class="muted">Nieuwe accounts worden als leerling aangemaakt en eerst ter goedkeuring aangeboden.</p>

        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <label for="name">Naam</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>

            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Wachtwoord</label>
            <input id="password" type="password" name="password" required>

            <label for="password_confirmation">Bevestig wachtwoord</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <div class="row" style="margin-top: 14px;">
                <button type="submit">Account maken</button>
                <a class="btn btn-muted" href="{{ route('login') }}">Terug naar login</a>
            </div>
        </form>
    </div>
@endsection
