<?php

namespace App\Events;

use App\Models\BankCard;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, SerializesModels;

    public BankCard|bool|null $oldCard;
    public ?BankCard $newCard;
    public float $amount;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(float $amount, User $user, BankCard|bool|null $oldCard = null, ?BankCard $newCard = null)
    {
        $this->oldCard = $oldCard;
        $this->newCard = $newCard;
        $this->amount = $amount;
        $this->user = $user;
    }
}
