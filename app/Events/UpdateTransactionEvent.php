<?php

namespace App\Events;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, SerializesModels;

    public Transaction $transaction;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Transaction $transaction, User $user)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }
}
