<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    /**
     * Get the students in this department.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the faculty members in this department.
     */
    public function faculty(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    /**
     * Get the subjects in this department.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the classes in this department.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(AcademicClass::class, 'department_id');
    }
}
