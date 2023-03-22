<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    /**
     * Get user related to transaction
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format amount attribute
     *
     * @return string
     */
    public function getAmountAttribute(): string
    {
        return number_format($this->attributes['amount']);
    }
}
