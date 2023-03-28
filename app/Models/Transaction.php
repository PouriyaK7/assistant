<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    protected $appends = ['formatted_amount'];

    /**
     * Get related user to transaction
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get related bank card to transaction
     *
     * @return BelongsTo
     */
    public function bankCard(): BelongsTo
    {
        return $this->belongsTo(BankCard::class);
    }

    /**
     * Format amount attribute
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->attributes['amount'], 2);
    }
}
