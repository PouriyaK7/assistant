<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Get all resources from db table with sort and search options
     *
     * @param array $sort
     * @param array $search
     * @return Collection
     */
    public function all(array $sort = [], array $search = []): Collection;

    /**
     * Fetch specific record from db with given field and value
     *
     * @param string $id
     * @param string $field
     * @return null|Model
     */
    public function get(string $id, string $field = 'id'): ?Model;

    /**
     * Update specific record from db
     *
     * @param string $id
     * @param array $data
     * @param string $field
     * @return bool
     */
    public function update(string $id, array $data, string $field = 'id'): bool;

    /**
     * Store new resource in db
     *
     * @param array $data
     * @return null|Model
     */
    public function store(array $data): ?Model;

    /**
     * Delete record from db
     *
     * @param string $id
     * @param string $field
     * @return bool
     */
    public function delete(string $id, string $field = 'id'): bool;
}
