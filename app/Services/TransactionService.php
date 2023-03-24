<?php

namespace App\Services;

use App\Models\Transaction;
use Exception;
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
     * @param string|null $id
     * @return int
     */
    public function update(string $title = null, int $amount = null, string $id = null): int
    {
        if (!is_null($amount)) {
            # Get difference between old and new amount
            $diff = $amount - $this->transaction->amount;
        }

        if (is_null($id)) {
            # Set new amount and update
            $this->transaction->amount = $amount ?? $this->transaction->amount;
            $this->transaction->title = $title ?? $this->transaction->title;
            $this->transaction->save();
        } else {
            # Set params for update and skip null ones
            $params = [];
            if (!is_null($title)) {
                $params['title'] = $title;
            }
            if (!is_null($amount)) {
                $params['amount'] = $amount;
            }

            # Update transaction
            Transaction::where('id', $id)
                ->update($params);
        }

        return $diff ?? 0;
    }

    /**
     * Delete transaction from db
     *
     * @param string|null $id
     * @return int|null
     */
    public function delete(string $id = null): ?int
    {
        if (!isset($this->transaction) && !empty($id)) {
            $this->transaction = Transaction::find($id);
        } elseif (!isset($this->transaction) && empty($id)) {
            return null;
        }

        # Get current amount to return at last
        $amount = -$this->transaction->amount;
        # Delete transaction
        $this->transaction->delete();

        return $amount;
    }
}
