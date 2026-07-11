<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['name', 'code', 'department_id'];

    /**
     * Get the department that owns the class.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the students in this class.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Get the examinations scheduled for this class.
     */
    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class, 'class_id');
    }

    /**
     * Get the study materials uploaded for this class.
     */
    public function studyMaterials(): HasMany
    {
        return $this->hasMany(StudyMaterial::class, 'class_id');
    }

    /**
     * Get the timetable slots for this class.
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }
}
