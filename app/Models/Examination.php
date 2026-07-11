<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examination extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'class_id',
        'duration_minutes',
        'total_marks',
        'exam_date',
        'is_published',
        'created_by_faculty_id'
    ];

    protected $casts = [
        'exam_date' => 'datetime',
        'is_published' => 'boolean'
    ];

    /**
     * Get the subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the class.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * Get the creator faculty member.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'created_by_faculty_id');
    }

    /**
     * Get the questions for this examination.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class, 'examination_id');
    }

    /**
     * Get the results generated for this exam.
     */
    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class, 'examination_id');
    }
}
