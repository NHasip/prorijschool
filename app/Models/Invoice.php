<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'payment_id',
        'invoice_number',
        'subtotal_cents',
        'vat_percent',
        'vat_cents',
        'total_cents',
        'issued_at',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_cents' => 'integer',
            'vat_percent' => 'integer',
            'vat_cents' => 'integer',
            'total_cents' => 'integer',
            'issued_at' => 'datetime',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
