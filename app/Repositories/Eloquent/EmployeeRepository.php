<?php

namespace App\Repositories\Eloquent;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository extends EloquentBaseRepository implements  EmployeeRepositoryInterface
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $employeeId
     * @return Collection
     */
    public function getCompany(int $employeeId): Collection
    {
        return new Collection();
    }
}
