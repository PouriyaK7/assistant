<?php

namespace App\Events;

use App\Models\BankCard;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, SerializesModels;

    public float $amount;
    public User $user;
    public ?BankCard $bankCard;

    /**
     * Create a new event instance.
     */
    public function __construct(float $amount, User $user, ?BankCard $bankCard = null)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->bankCard = $bankCard;
    }
}
