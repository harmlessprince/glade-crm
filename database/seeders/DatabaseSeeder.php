<?php

namespace Database\Seeders;

use App\Constants\RoleType;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create(['role' => RoleType::ADMIN]);
        $this->call([
            CompanySeeder::class,
            EmployeeSeeder::class,
        ]);

    }
}
