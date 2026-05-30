@extends('layouts.app', ['title' => '2FA verificatie'])

@section('content')
    <div class="card">
        <h1>2FA verificatie</h1>
        <p class="muted">Voer de 6-cijferige code in die is verzonden naar <strong>{{ $email }}</strong>.</p>

        <form method="post" action="{{ route('2fa.verify') }}">
            @csrf
            <label for="code">Verificatiecode</label>
            <input id="code" type="text" name="code" inputmode="numeric" maxlength="6" required>

            <div class="row" style="margin-top: 14px;">
                <button type="submit">Code bevestigen</button>
            </div>
        </form>

        <form method="post" action="{{ route('2fa.resend') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-muted">Nieuwe code sturen</button>
        </form>
    </div>
@endsection
