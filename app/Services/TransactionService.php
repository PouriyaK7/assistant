<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Ramsey\Uuid\Uuid;

class TransactionService
{
    /**
     * Store transaction in db
     *
     * @param string $title
     * @param float $amount
     * @param string $userID
     * @param string|null $bankCardID
     * @return null|Transaction
     */
    public static function create(string $title, float $amount, string $userID, string $bankCardID = null): ?Transaction
    {
        return (new TransactionRepository())->store([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $userID,
            'amount' => $amount,
            'title' => $title,
            'bank_card_id' => $bankCardID,
        ]);
    }

    /**
     * Update an existing transaction in db
     *
     * @param string $id
     * @param string|null $title
     * @param float|null $amount
     * @param string|null $bankCardID
     * @return float
     */
    public static function update(string $id, string $title = null, float $amount = null, string $bankCardID = null): float
    {
        $repo = new TransactionRepository();
        $transaction = $repo->get($id);

        if (!is_null($amount)) {
            # Get difference between old and new amount
            $diff = round($amount - (float)$transaction->amount, 2);
        }

        # Update transaction
        $repo->update($id, [
            'amount' => $amount ?? $transaction->amount,
            'title' => $title ?? $transaction->title,
            'bank_card_id' => $bankCardID ?? $transaction->bank_card_id,
        ]);

        return $diff ?? 0;
    }

    /**
     * Delete transaction from db
     *
     * @param string $id
     * @return float
     */
    public static function delete(string $id): float
    {
        $repo = new TransactionRepository();
        $transaction = $repo->get($id);

        # Get current amount to return at last
        $amount = -$transaction->amount;
        # Delete transaction
        $repo->delete($id);

        return $amount;
    }

    public static function get(string $id): ?Transaction
    {
        return (new TransactionRepository())->get($id);
    }
}
