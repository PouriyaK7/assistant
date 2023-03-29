<?php

namespace App\Services;

use App\Models\BankCard;
use App\Models\User;
use App\Repositories\BankCardRepository;
use Ramsey\Uuid\Uuid;

class BankCardService
{
    /**
     * Store new bank card in db
     *
     * @param string $title
     * @param string $number
     * @param int $userID
     * @return null|BankCard
     */
    public static function create(string $title, string $number, int $userID): ?BankCard
    {
        # Store bank card in db and return false on failure
        return (new BankCardRepository())->store([
            'id' => Uuid::uuid4()->toString(),
            'title' => $title,
            'number' => $number,
            'user_id' => $userID,
        ]);
    }

    /**
     * Update existing bank card in db
     *
     * @param string $id
     * @param string $title
     * @param string $number
     * @return bool
     */
    public static function update(string $id, string $title, string $number): bool
    {
        return (new BankCardRepository())->update($id, [
            'title' => $title,
            'number' => $number,
        ]);
    }

    /**
     * Delete bank card from db if it has no transactions
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        $repo = new BankCardRepository();

        # Check if bank card has no transactions
        if ($repo->hasTransaction($id)) {
            return false;
        }

        return $repo->delete($id);
    }

    public static function get(string $id): ?BankCard
    {
        return (new BankCardRepository())->get($id);
    }

    /**
     * Transfer an amount between two bank cards
     *
     * @param float $amount
     * @param BankCard|User $originCard
     * @param BankCard|User $destCard
     * @return void
     */
    public static function transfer(float $amount, BankCard|User $originCard, BankCard|User $destCard): void
    {
        $originCard->balance -= $amount;
        $originCard->save();

        $destCard->balance += $amount;
        $destCard->save();
    }
}
