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
     * @param string $title
     * @param int $amount
     * @param string $userID
     * @return string
     */
    public function create(string $title, int $amount, string $userID): string
    {
        $id = Uuid::uuid4()->toString();
        $this->transaction = Transaction::create([
            'id' => $id,
            'user_id' => $userID,
            'amount' => $amount,
            'title' => $title,
        ]);

        return $id;
    }

    /**
     * Update an existing transaction in db
     *
     * @param string|null $title
     * @param int|null $amount
     * @return int
     */
    public function update(string $title = null, int $amount = null): int
    {
        if (!is_null($amount)) {
            # Get difference between old and new amount
            $diff = $amount - $this->transaction->amount;
        }

        # Set new amount and update
        $this->transaction->amount = $amount ?? $this->transaction->amount;
        $this->transaction->title = $title ?? $this->transaction->title;
        $this->transaction->save();

        return $diff ?? 0;
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
