@extends('layouts.portal', ['title' => 'Instructeurs'])

@section('content')
    <div class="card">
        <h1>Instructeurs Beheren</h1>
        <p class="muted">Overzicht van actieve instructeurs en hun huidige leerlingbelasting.</p>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>Naam</th>
                <th>E-mail</th>
                <th>Status</th>
                <th>Actieve Leerlingen</th>
            </tr>
            </thead>
            <tbody>
            @forelse($instructors as $instructor)
                <tr>
                    <td><strong>{{ $instructor->name }}</strong></td>
                    <td>{{ $instructor->email }}</td>
                    <td>{{ $instructor->approval_status }}</td>
                    <td>{{ $instructor->instructed_students_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Geen instructeurs gevonden.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            {{ $instructors->links() }}
        </div>
    </div>
@endsection

