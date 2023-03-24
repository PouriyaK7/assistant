<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, SerializesModels;

    public float $amount;
    public string $userID;

    /**
     * Create a new event instance.
     */
    public function __construct(float $amount, string $userID)
    {
        $this->userID = $userID;
        $this->amount = $amount;
    }
}
