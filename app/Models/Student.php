<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'instructor_user_id',
        'student_number',
        'phone',
        'birth_date',
        'lesson_balance_minutes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'lesson_balance_minutes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function instructorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
