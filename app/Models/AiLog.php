<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feature_used',
        'prompt',
        'response',
        'tokens_used'
    ];

    /**
     * Get the user who executed the AI feature.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
