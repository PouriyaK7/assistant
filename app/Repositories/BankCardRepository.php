<?php

namespace App\Repositories;

use App\Models\BankCard;
use App\Models\Transaction;
use App\Repositories\Contracts\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BankCardRepository implements Repository
{
    public function all(array $sort = [], array $search = []): Collection
    {
        $cards = BankCard::query();
        if (!empty($sort)) {
            $cards = $cards->orderBy($sort['field'], $sort['order']);
        }
        if (!empty($search)) {
            $cards = $cards->where($search['field'], 'LIKE', "%{$search['value']}%");
        }

        return $cards->get();
    }

    public function get(string $id, string $field = 'id'): ?BankCard
    {
        return BankCard::where($field, $id)->first();
    }

    public function update(string $id, array $data, string $field = 'id'): bool
    {
        return BankCard::where($field, $id)->update($data);
    }

    public function store(array $data): ?BankCard
    {
        return BankCard::create($data);
    }

    public function delete(string $id, string $field = 'id'): bool
    {
        return BankCard::where($field, $id)->delete();
    }

    /**
     * Returns true if bank card has transactions
     *
     * @param string $id
     * @return bool
     */
    public function hasTransaction(string $id): bool
    {
        return Transaction::where('bank_card_id', $id)->exists();
    }
}
