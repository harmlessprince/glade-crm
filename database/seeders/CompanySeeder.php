<?php

namespace Database\Seeders;

use App\Constants\RoleType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)->create(['role' => RoleType::COMPANY])->each(function ($user)
        {
            $company = Company::factory()->make();
            $user->company()->save($company);
        });
    }
}
