<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id',
        'student_id',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'marks_obtained',
        'passed',
        'answers_json'
    ];

    protected $casts = [
        'passed' => 'boolean',
        'answers_json' => 'array'
    ];

    /**
     * Get the exam.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Examination::class, 'examination_id');
    }

    /**
     * Get the student who took the exam.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
