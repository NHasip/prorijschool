@extends('layouts.app', ['title' => 'Registreren'])

@section('content')
    <div class="card">
        <h1>Registreren</h1>
        <p class="muted">Leerlingen worden eerst ter goedkeuring aangeboden aan de rijschool.</p>

        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <label for="name">Naam</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>

            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>

            <label for="role">Rol</label>
            <select id="role" name="role" required>
                <option value="leerling" @selected(old('role', 'leerling') === 'leerling')>Leerling</option>
                <option value="instructeur" @selected(old('role') === 'instructeur')>Instructeur</option>
                <option value="beheerder" @selected(old('role') === 'beheerder')>Rijschoolbeheerder</option>
            </select>

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
