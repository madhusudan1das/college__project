<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';

    protected $fillable = [
        'user_id',
        'department_id',
        'designation',
        'qualification',
        'joining_date',
        'gender',
        'address'
    ];

    /**
     * Get the user account for this faculty.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department this faculty belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the subjects this faculty teaches.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_faculty');
    }

    /**
     * Get the attendance marked by this faculty.
     */
    public function attendanceMarked(): HasMany
    {
        return $this->hasMany(Attendance::class, 'marked_by_faculty_id');
    }

    /**
     * Get the study materials uploaded by this faculty.
     */
    public function studyMaterials(): HasMany
    {
        return $this->hasMany(StudyMaterial::class, 'uploaded_by_faculty_id');
    }

    /**
     * Get the examinations created by this faculty.
     */
    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class, 'created_by_faculty_id');
    }

    /**
     * Get the salary records for this faculty member.
     */
    public function salaryRecords(): HasMany
    {
        return $this->hasMany(SalaryRecord::class);
    }

    /**
     * Get the timetable slots for this faculty member.
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}
