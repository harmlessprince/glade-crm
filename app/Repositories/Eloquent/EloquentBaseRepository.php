<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentBaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /**
     * Get all models.
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Find model by id.
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->findorFail($modelId)->append($appends);
    }
    /**
     * Create a model.
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }
    /**
     * Update existing model.
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        return $model->update($payload);
    }
    /**
     * Delete model by id.
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * @param array $columns
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $columns = ['*'], int $perPage = 10): LengthAwarePaginator
    {
        return $this->model::query()->select($columns)->paginate($perPage);
    }
}
