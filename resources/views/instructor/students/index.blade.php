@extends('layouts.portal', ['title' => 'Mijn Leerlingen'])

@section('content')
    <div class="card">
        <h1>Mijn Leerlingen</h1>
        <p class="muted">Overzicht van leerlingen gekoppeld aan jouw instructeursaccount.</p>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>Leerling</th>
                <th>Nummer</th>
                <th>Telefoon</th>
                <th>Laatste Les</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($students as $student)
                @php($latestLesson = $student->lessons->first())
                <tr>
                    <td>{{ $student->user->name }}<br><span class="muted">{{ $student->user->email }}</span></td>
                    <td>{{ $student->student_number ?: '-' }}</td>
                    <td>{{ $student->phone ?: '-' }}</td>
                    <td>{{ $latestLesson?->starts_at?->format('d-m-Y H:i') ?? '-' }}</td>
                    <td>{{ $student->user->approval_status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nog geen gekoppelde leerlingen.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            {{ $students->links() }}
        </div>
    </div>
@endsection
