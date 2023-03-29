<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Contracts\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository implements Repository
{

    public function all(array $sort = [], array $search = []): Collection
    {
        $transactions = Transaction::query();
        if (!empty($sort)) {
            $transactions = $transactions->orderBy($sort['field'], $sort['order']);
        }
        if (!empty($search)) {
            $transactions = $transactions->where($search['field'], 'LIKE', "%{$search['value']}%");
        }

        return $transactions->get();
    }

    public function get(string $id, string $field = 'id'): ?Transaction
    {
        return Transaction::where($field, $id)->first();
    }

    public function update(string $id, array $data, string $field = 'id'): bool
    {
        return Transaction::where($field, $id)->update($data);
    }

    public function store(array $data): ?Transaction
    {
        return Transaction::create($data);
    }

    public function delete(string $id, string $field = 'id'): bool
    {
        return Transaction::where($field, $id)->delete();
    }
}
