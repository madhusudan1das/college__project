<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'amount',
        'due_date',
        'status'
    ];

    /**
     * Get the student assigned to this fee.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the payments made towards this fee.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
