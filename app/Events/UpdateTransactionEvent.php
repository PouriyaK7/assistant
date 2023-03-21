<?php

namespace App\Events;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, SerializesModels;

    public int $amount;
    public string $userID;

    /**
     * Create a new event instance.
     */
    public function __construct(int $amount, string $userID)
    {
        $this->userID = $userID;
        $this->amount = $amount;
    }
}
