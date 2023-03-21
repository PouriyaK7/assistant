<?php

namespace App\Services;

use App\Models\Transaction;
use Ramsey\Uuid\Uuid;

class TransactionService
{
    private Transaction $transaction;

    public function __construct(string $id = null)
    {
        if (!is_null($id)) {
            $this->transaction = Transaction::find($id);
        }
    }

    /**
     * Get transaction instance
     *
     * @return Transaction|null
     */
    public function get(): ?Transaction
    {
        return $this->transaction ?? null;
    }

    /**
     * Store transaction in db
     *
     * @param int $amount
     * @param string $userID
     * @return Transaction
     */
    public function create(int $amount, string $userID): Transaction
    {
        $this->transaction = Transaction::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $userID,
            'amount' => $amount,
        ]);

        return $this->transaction;
    }

    /**
     * Update an existing transaction in db
     *
     * @param int $amount
     * @return int
     */
    public function update(int $amount): int
    {
        # Get difference between old and new amount
        $diff =  $amount - $this->transaction->amount;

        # Set new amount and update
        $this->transaction->amount = $amount;
        $this->transaction->save();

        return $diff;
    }

    /**
     * Delete transaction from db
     *
     * @return bool
     */
    public function delete(): bool
    {
        # Get current amount to return at last
        $amount = -$this->transaction->amount;
        # Delete transaction
        $this->transaction->delete();

        return $amount;
    }
}
