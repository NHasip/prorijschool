@extends('layouts.app', ['title' => 'Mijn Facturen'])

@php
    $fmt = static fn (int $cents): string => 'EUR '.number_format($cents / 100, 2, ',', '.');
@endphp

@section('content')
    <div class="card">
        <h1>Mijn Facturen</h1>
        <p class="muted">Overzicht van facturen en betaalstatus.</p>
    </div>

    <div class="card">
        @if(! $student)
            <p>Er is nog geen leerlingprofiel gekoppeld aan je account.</p>
        @elseif($payments?->isEmpty())
            <p>Nog geen facturen beschikbaar.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Factuurnummer</th>
                    <th>Pakket</th>
                    <th>Status</th>
                    <th>Subtotaal</th>
                    <th>BTW</th>
                    <th>Totaal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->invoice?->invoice_number ?? '-' }}</td>
                        <td>{{ $payment->lessonPackage?->name ?? '-' }}</td>
                        <td>{{ $payment->status }}</td>
                        <td>{{ $fmt($payment->invoice?->subtotal_cents ?? $payment->amount_cents) }}</td>
                        <td>{{ $payment->invoice ? $payment->invoice->vat_percent.'%' : '-' }}</td>
                        <td>{{ $payment->invoice ? $fmt($payment->invoice->total_cents) : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="margin-top: 14px;">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection

