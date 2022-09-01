<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository extends EloquentBaseRepository implements CompanyRepositoryInterface
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $companyId
     * @return Collection
     */
    public function getEmployees(int $companyId): Collection
    {
        return new  Collection();
    }
}
