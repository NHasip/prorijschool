@extends('layouts.portal', ['title' => 'Financien'])

@php
    $fmt = static fn (int $cents): string => 'EUR '.number_format($cents / 100, 2, ',', '.');
    $canCreateInvoice = \Illuminate\Support\Facades\Route::has('admin.finance.create-invoice');
    $canUpdatePaymentStatus = \Illuminate\Support\Facades\Route::has('admin.finance.update-payment-status');
@endphp

@section('content')
    <div class="card">
        <h1>Financien & Facturen</h1>
        <p class="muted">Live financieel overzicht met 21% BTW op nieuwe facturen.</p>
        <div class="row" style="margin-top: 10px;">
            <span><strong>Openstaand:</strong> {{ $fmt($totals['openstaand_cents']) }}</span>
            <span><strong>Betaald:</strong> {{ $fmt($totals['betaald_cents']) }}</span>
            <span><strong>Facturen:</strong> {{ $totals['facturen_count'] }}</span>
        </div>
    </div>

    <div class="card">
        <h2>Nieuwe Factuur Maken</h2>
        @if(! $canCreateInvoice)
            <p class="error">Financiele acties nog niet beschikbaar. Draai deploy opnieuw zodat de nieuwste routes geladen zijn.</p>
        @endif
        <form method="post" action="{{ $canCreateInvoice ? route('admin.finance.create-invoice') : '#' }}">
            @csrf
            <label for="student_id">Leerling</label>
            <select id="student_id" name="student_id" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">
                        {{ $student->student_number ?: 'Leerling #'.$student->id }} - {{ $student->user->name }}
                    </option>
                @endforeach
            </select>

            <label for="lesson_package_id">Pakket (optioneel)</label>
            <select id="lesson_package_id" name="lesson_package_id">
                <option value="">Geen pakket</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                @endforeach
            </select>

            <label for="subtotal_eur">Bedrag exclusief BTW (EUR)</label>
            <input id="subtotal_eur" type="number" name="subtotal_eur" step="0.01" min="0.01" required>

            <div class="row" style="margin-top: 12px;">
                <button type="submit" @disabled(! $canCreateInvoice)>Factuur Maken (21% BTW)</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Betalingen</h2>
        <table>
            <thead>
            <tr>
                <th>Factuur</th>
                <th>Leerling</th>
                <th>Pakket</th>
                <th>Status</th>
                <th>Subtotaal</th>
                <th>Totaal incl. BTW</th>
                <th>Actie</th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->invoice?->invoice_number ?? '-' }}</td>
                    <td>{{ $payment->student?->user?->name ?? '-' }}</td>
                    <td>{{ $payment->lessonPackage?->name ?? '-' }}</td>
                    <td>{{ $payment->status }}</td>
                    <td>{{ $fmt($payment->amount_cents) }}</td>
                    <td>{{ $payment->invoice ? $fmt($payment->invoice->total_cents) : '-' }}</td>
                    <td>
                        <form method="post" action="{{ $canUpdatePaymentStatus ? route('admin.finance.update-payment-status', $payment) : '#' }}" class="row">
                            @csrf
                            <select name="status" style="width: 120px;">
                                @foreach(['open', 'pending', 'paid', 'failed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($payment->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" @disabled(! $canUpdatePaymentStatus)>Opslaan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Geen betalingen gevonden.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            {{ $payments->links() }}
        </div>
    </div>
@endsection
