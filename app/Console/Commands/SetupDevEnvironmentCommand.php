<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Constants\RoleType;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SetupDevEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the development environment for testing and seeding';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up development environment');
        $this->info('Step 1: refresh database');
        $this->migrateFresh();
        $this->info('Step 2: seed database');
        $this->info('Step 2.1: Create super admin');
        $this->createSuperAdmin();
        $this->info('Step 2.1: Seed Company and Employee data');
        $this->SeedDatabase();
        $this->info('All done. Bye!');
    }
    private function SeedDatabase()
    {
        $this->call('db:seed');
    }
    private function migrateFresh()
    {
        $this->call('migrate:fresh');
    }
    private function createSuperAdmin()
    {
        $this->info('Creating Super Admin');
        $user = User::updateOrCreate([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com ',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => RoleType::SUPER_ADMIN,
        ]);
        $this->info('Super Admin created');
        $this->warn('Email: superadmin@admin.com');
        $this->warn('Password: password');
        return $user;
    }
}
