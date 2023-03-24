<?php

namespace App\Services;

use App\Models\Transaction;
use Exception;
use Ramsey\Uuid\Uuid;

class TransactionService
{
    private ?Transaction $transaction;

    public function __construct(string|Transaction $transaction = null)
    {
        if (!is_null($transaction)) {
            if (!($transaction instanceof Transaction)) {
                $this->transaction = Transaction::find($transaction);
            } else {
                $this->transaction = $transaction;
            }
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
     * @param string|Transaction $transaction
     * @return void
     * @throws Exception
     */
    public function set(string|Transaction $transaction): void
    {
        # Fetch transaction from db if id was given in func param
        if (!($transaction instanceof Transaction)) {
            $transaction = Transaction::find($transaction);
        }

        # Throw an exception if transaction not existed
        if (empty($transaction)) {
            throw new Exception('Transaction is empty');
        }

        $this->transaction = $transaction;
    }

    /**
     * Store transaction in db
     *
     * @param string $title
     * @param float $amount
     * @param string $userID
     * @return string
     */
    public function create(string $title, float $amount, string $userID): string
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
     * @param float|null $amount
     * @return float
     */
    public function update(string $title = null, float $amount = null): float
    {
        if (!is_null($amount)) {
            # Get difference between old and new amount
            $diff = round($amount - (float)$this->transaction->amount, 2);
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
     * @return float
     */
    public function delete(): float
    {
        # Get current amount to return at last
        $amount = -$this->transaction->amount;
        # Delete transaction
        $this->transaction->delete();

        return $amount;
    }
}
