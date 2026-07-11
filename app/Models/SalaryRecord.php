<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'base_salary',
        'bonuses',
        'deductions',
        'net_salary',
        'payment_date',
        'status'
    ];

    /**
     * Get the faculty member.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}
