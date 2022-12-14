<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

interface EloquentRepositoryInterface
{
    /**
     * Get all models.
     * @param array $columns *
     * @param array $relations *
     * @return Collection
     *
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get all models in paginated form.
     * @param array $columns
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $columns = ['*'], int $perPage = 10): LengthAwarePaginator;

    /**
     * Find model by id.
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */

    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model;

    /**
     * Create a model.
     * @param array $payload * @return Model
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model.
     * @param int $modelId * @param array $payload
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool;

    /**
     * Delete model by id.
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool;
}
