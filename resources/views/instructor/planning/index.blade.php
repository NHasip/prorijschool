@extends('layouts.app', ['title' => 'Lesplanning'])

@section('content')
    <div class="card">
        <h1>Lesplanning</h1>
        <p class="muted">Plan lessen in en beheer de status van bestaande lessen.</p>
    </div>

    <div class="card">
        <h2>Nieuwe Les Inplannen</h2>
        <form method="post" action="{{ route('instructor.planning.store') }}">
            @csrf
            <label for="student_id">Leerling</label>
            <select id="student_id" name="student_id" required>
                @foreach($assignedStudents as $student)
                    <option value="{{ $student->id }}">
                        {{ $student->student_number ?: '#'.$student->id }} - {{ $student->user->name }}
                    </option>
                @endforeach
            </select>

            <label for="starts_at">Start</label>
            <input id="starts_at" type="datetime-local" name="starts_at" required>

            <label for="ends_at">Einde</label>
            <input id="ends_at" type="datetime-local" name="ends_at" required>

            <label for="location">Locatie</label>
            <input id="location" type="text" name="location" placeholder="Bijv. Utrecht Centrum">

            <label for="lesson_type">Type</label>
            <input id="lesson_type" type="text" name="lesson_type" value="praktijkles" required>

            <label for="notes">Notities</label>
            <input id="notes" type="text" name="notes">

            <div class="row" style="margin-top: 12px;">
                <button type="submit">Les Opslaan</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Geplande Lessen</h2>
        <table>
            <thead>
            <tr>
                <th>Leerling</th>
                <th>Start</th>
                <th>Einde</th>
                <th>Locatie</th>
                <th>Status</th>
                <th>Actie</th>
            </tr>
            </thead>
            <tbody>
            @forelse($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->student->user->name }}</td>
                    <td>{{ $lesson->starts_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $lesson->ends_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $lesson->location ?: '-' }}</td>
                    <td>{{ $lesson->status }}</td>
                    <td>
                        <form method="post" action="{{ route('instructor.planning.update-status', $lesson) }}" class="row">
                            @csrf
                            <select name="status" style="width: 120px;">
                                @foreach(['planned', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($lesson->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Opslaan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nog geen lessen ingepland.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            {{ $lessons->links() }}
        </div>
    </div>
@endsection

