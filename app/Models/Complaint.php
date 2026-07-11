<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'category',
        'status',
        'ai_comment'
    ];

    /**
     * Get the student who filed this complaint.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
