<?php

namespace App\Constants;

use App\Enums\BaseEnum;

class RoleType extends BaseEnum
{
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const COMPANY = 3;
    const EMPLOYEE = 4;
}
