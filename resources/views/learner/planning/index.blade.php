@extends('layouts.portal', ['title' => 'Mijn Planning'])

@section('content')
    <div class="card">
        <h1>Mijn Planning</h1>
        <p class="muted">Aankomende en afgeronde rijlessen.</p>
    </div>

    <div class="card">
        @if(! $student)
            <p>Er is nog geen leerlingprofiel gekoppeld aan je account.</p>
        @elseif($lessons?->isEmpty())
            <p>Er zijn nog geen lessen ingepland.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Start</th>
                    <th>Einde</th>
                    <th>Instructeur</th>
                    <th>Locatie</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lessons as $lesson)
                    <tr>
                        <td>{{ $lesson->starts_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $lesson->ends_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $lesson->instructorUser?->name ?? '-' }}</td>
                        <td>{{ $lesson->location ?: '-' }}</td>
                        <td>{{ $lesson->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="margin-top: 14px;">
                {{ $lessons->links() }}
            </div>
        @endif
    </div>
@endsection
