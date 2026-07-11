<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_id',
        'student_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'transaction_id',
        'status'
    ];

    /**
     * Get the fee invoice.
     */
    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    /**
     * Get the student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the receipt generated for this payment.
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }
}
