@extends('layouts.portal', ['title' => 'Leerlingenbeheer'])

@section('content')
    <div class="card">
        <h1>Mijn Leerlingen</h1>
        <p class="muted">Live overzicht van leerlingen, status en instructeur-koppeling.</p>

        <form method="get" action="{{ route('admin.students.index') }}" class="row" style="margin-top: 10px;">
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Zoek op naam, e-mail of leerlingnummer" style="max-width: 320px;">
            <select name="status" style="max-width: 200px;">
                <option value="">Alle statussen</option>
                <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
                <option value="approved" @selected($filters['status'] === 'approved')>Approved</option>
                <option value="rejected" @selected($filters['status'] === 'rejected')>Rejected</option>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>Leerling</th>
                <th>Nummer</th>
                <th>Status</th>
                <th>Instructeur</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            @forelse($students as $student)
                <tr>
                    <td>
                        <strong>{{ $student->user->name }}</strong><br>
                        <span class="muted">{{ $student->user->email }}</span>
                    </td>
                    <td>{{ $student->student_number ?: '-' }}</td>
                    <td>{{ $student->user->approval_status }}</td>
                    <td>{{ $student->instructorUser?->name ?? 'Niet gekoppeld' }}</td>
                    <td>
                        <div class="row">
                            <form method="post" action="{{ route('admin.students.assign-instructor', $student) }}">
                                @csrf
                                <select name="instructor_user_id" style="width: 180px;">
                                    <option value="">Geen instructeur</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" @selected($student->instructor_user_id === $instructor->id)>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit">Koppel</button>
                            </form>
                            <form method="post" action="{{ route('admin.students.update-status', $student) }}">
                                @csrf
                                <select name="approval_status" style="width: 120px;">
                                    <option value="pending" @selected($student->user->approval_status === 'pending')>Pending</option>
                                    <option value="approved" @selected($student->user->approval_status === 'approved')>Approved</option>
                                    <option value="rejected" @selected($student->user->approval_status === 'rejected')>Rejected</option>
                                </select>
                                <button type="submit">Status</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Geen leerlingen gevonden.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            {{ $students->links() }}
        </div>
    </div>
@endsection
