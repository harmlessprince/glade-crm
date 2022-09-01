<?php

namespace App\Repositories\Eloquent;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository extends EloquentBaseRepository implements  EmployeeRepositoryInterface
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }
    /**
     * @param int $companyId
     * @return LengthAwarePaginator
     */
    public function getEmployees(int $companyId): LengthAwarePaginator
    {
        return $this->model::query()->where('company_id', $companyId)->paginate(10);
    }
}
