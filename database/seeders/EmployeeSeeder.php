<?php

namespace Database\Seeders;

use App\Constants\RoleType;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::all()->each(function ($company) {
            $employees = Employee::factory(random_int(15, 25))->make()->each(function ($employee)  {
                $employee->user_id = User::factory()->create(['role' => RoleType::EMPLOYEE])->id;
            });
            $company->employees()->saveMany($employees);
        });
    }
}
