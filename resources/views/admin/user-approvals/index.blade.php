@extends('layouts.portal', ['title' => 'Leerlingen Goedkeuring'])

@section('content')
    <div class="card">
        <h1>Leerlingen Goedkeuring</h1>
        <p class="muted">Nieuwe leerlingregistraties met status "pending".</p>

        @if($pendingUsers->isEmpty())
            <p>Geen leerlingen die wachten op goedkeuring.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Naam</th>
                    <th>E-mail</th>
                    <th>Aangemaakt</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendingUsers as $pendingUser)
                    <tr>
                        <td>{{ $pendingUser->name }}</td>
                        <td>{{ $pendingUser->email }}</td>
                        <td>{{ $pendingUser->created_at?->format('d-m-Y H:i') }}</td>
                        <td>
                            <div class="row">
                                <form method="post" action="{{ route('admin.approvals.approve', $pendingUser) }}">
                                    @csrf
                                    <button type="submit">Goedkeuren</button>
                                </form>
                                <form method="post" action="{{ route('admin.approvals.reject', $pendingUser) }}">
                                    @csrf
                                    <button class="btn btn-danger" type="submit">Afwijzen</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <a class="btn btn-muted" href="{{ route('dashboard') }}">Terug naar dashboard</a>
    </div>
@endsection
