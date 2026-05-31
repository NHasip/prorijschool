<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class LessonPackage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'package_type',
        'lesson_count',
        'lesson_minutes',
        'price_cents',
        'vat_percent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'lesson_count' => 'integer',
            'lesson_minutes' => 'integer',
            'price_cents' => 'integer',
            'vat_percent' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
