<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get company employees.
     * @param int $companyId
     * @return LengthAwarePaginator
     *
     */
    public function getEmployees(int $companyId): LengthAwarePaginator;
}
