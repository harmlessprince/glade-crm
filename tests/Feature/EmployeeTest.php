<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;


    /**
     * @return void
     */
    public function test_store_method_binds_employee_repository_interface()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $companyOwner = $this->createUser(RoleType::COMPANY);
        $company = $this->createCompany($companyOwner->id);
        $employeeUser = $this->createUser(RoleType::EMPLOYEE);
        $employee = Employee::factory()->create(['company_id' => $company->id, 'user_id' => $this->createUser(RoleType::EMPLOYEE)->id]);
        $repository = \Mockery::mock(EmployeeRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->andReturn($employee);
        $this->app->instance(EmployeeRepositoryInterface::class, $repository);
        $response = $this->call('POST', route('employees.store'), $this->employeeData($employeeUser->id, $company->id));
        $this->assertDatabaseCount('employees', 1);
        $response->assertOk();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_super_admin_can_create_employee_with_required_credentials()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $employeeUser = $this->createUser(RoleType::EMPLOYEE);
        $companyUser = $this->createUser(RoleType::COMPANY);
        $company  = Company::factory()->create(['user_id' => $companyUser->id]);
        $response = $this->postJson(route('employees.store'), $this->employeeData($employeeUser->id, $company->id));
        $response->assertStatus(200);
        $this->assertDatabaseCount('employees', 1);
    }

    /**
     * @return void
     */
    public function test_super_admin_can_see_company_employees_by_supplying_company_id()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $companyOwner = $this->createUser(RoleType::COMPANY);
        $company = Company::factory()->create(['user_id' => $companyOwner->id]);
        $response = $this->get(route('employees.index', ['company' => $company]));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_admin_can_see_company_employees_by_supplying_company_id()
    {
        $authUser = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($authUser);
        $companyOwner = $this->createUser(RoleType::COMPANY);
        $company = Company::factory()->create(['user_id' => $companyOwner->id]);
        $response = $this->get(route('employees.index', ['company' => $company]));
        $response->assertStatus(200);
    }
    /**
     * @return void
     */
    public function test_employee_can_not_see_company_employees()
    {
        $authUser = $this->createUser(RoleType::EMPLOYEE);
        Sanctum::actingAs($authUser);
        $companyOwner = $this->createUser(RoleType::COMPANY);
        $company = Company::factory()->create(['user_id' => $companyOwner->id]);
        $response = $this->get(route('employees.index', ['company' => $company]));
        $response->assertStatus(403);
    }
    /**
     * @return void
     */
    public function test_company_owner_can_see_all_employees_that_belongs_to_his_company()
    {
        $authUser = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $authUser->id]);
        $response = $this->get(route('employees.index', ['company' => $company]));
        $response->assertStatus(200);
    }

    

    private function createUser($role)
    {
        return User::factory()->create(['role' => $role]);
    }

    private function createCompany($user_id)
    {
        return Company::factory()->create(['user_id' => $user_id]);
    }

    private function companyData($user_id = null)
    {
        return ['user_id' => $user_id, 'name' => 'Company 1'];
    }

    private function employeeData(int $user_id , int $company_id): array
    {
        return ['user_id' => $user_id, 'first_name' => 'First name', 'last_name' => 'Last Name', 'company_id' => $company_id];
    }
}
