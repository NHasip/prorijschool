<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = [
        'student_id',
        'instructor_user_id',
        'starts_at',
        'ends_at',
        'location',
        'lesson_type',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function instructorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }
}

