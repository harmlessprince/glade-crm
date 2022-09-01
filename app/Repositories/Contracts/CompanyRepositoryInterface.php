<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get company employees.
     * @param int $companyId
     * @return Collection
     *
     */
    public function getEmployees(int $companyId): Collection;
}
