<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends EloquentBaseRepository implements  UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
