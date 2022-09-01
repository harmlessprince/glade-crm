<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get employee company.
     * @param int $employeeId
     * @return Collection
     *
     */
    public function getCompany( int $employeeId): Collection;
}
