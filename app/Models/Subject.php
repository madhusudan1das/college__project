<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'department_id'];

    /**
     * Get the department that owns the subject.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the faculty members who teach this subject.
     */
    public function faculty(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'subject_faculty');
    }

    /**
     * Get the study materials uploaded for this subject.
     */
    public function studyMaterials(): HasMany
    {
        return $this->hasMany(StudyMaterial::class);
    }

    /**
     * Get the examinations for this subject.
     */
    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class);
    }
}
