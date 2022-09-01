<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\CompanyCreatedNotification;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;

    /**
     * @return void
     */
    public function test_index_method_binds_company_repository_interface()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $user1 = User::factory()->create(['role' => RoleType::COMPANY]);
        $user2 = User::factory()->create(['role' => RoleType::COMPANY]);
        Company::factory()->create(['user_id' => $user1->id]);
        Company::factory()->create(['user_id' => $user2->id]);
        $repository = \Mockery::mock(CompanyRepositoryInterface::class);
        $repository->shouldReceive('getPaginated')->once()->andReturn(Company::paginate(10));
        $this->app->instance(CompanyRepositoryInterface::class, $repository);
        $response = $this->call('GET', route('companies.index'));
        $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_super_admin_can_view_all_companies()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $response = $this->getJson(route('companies.index'));
        $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_a_none_super_admin_can_not_view_all_companies()
    {
        $authUser = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($authUser);
        $response = $this->getJson(route('companies.index'));
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_a_company_can_not_be_created_without_required_fields()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $response = $this->postJson(route('companies.store'));
        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_a_super_admin_can_create_a_company_with_required_fields()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $response = $this->postJson(route('companies.store'), $this->companyData($user->id));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_a_admin_can_create_a_company_with_required_fields()
    {
        $authUser = $this->createUser(RoleType::ADMIN);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $response = $this->postJson(route('companies.store'), $this->companyData($user->id));
        $response->assertJson([
            "success" => true,
            "message" => "Company created successfully",
            "result" => [
                "id" => 1,
                "name" => "Company 1",
                "email" => null,
                "website" => null
            ]
        ]);
        $response->assertStatus(200);
    }
    /**
     * @return void
     */
    public function test_notification_is_sent_to_company_owner_account_once_company_is_created()
    {
        Notification::fake();
        $authUser = $this->createUser(RoleType::ADMIN);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $response = $this->postJson(route('companies.store'), $this->companyData($user->id));
        Notification::assertSentTo($user, CompanyCreatedNotification::class);
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_none_admin_user_can_not_create_a_company_with_required_fields()
    {

        $authUser = $this->createUser(RoleType::COMPANY);
        $user = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($authUser);
        $response = $this->postJson(route('companies.store'), $this->companyData($user->id));
        $response->assertStatus(403);
    }

    public function test_a_user_with_role_of_type_admin_can_view_any_company()
    {

        $authUser = $this->createUser(RoleType::ADMIN);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $user->id]);
        $response = $this->getJson(route('companies.show', $company->id));
        $response->assertStatus(200);
    }
    public function test_a_user_with_role_of_type_super_admin_can_view_any_company()
    {

        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $user->id]);
        $response = $this->getJson(route('companies.show', $company->id));
        $response->assertStatus(200);
    }

    public function test_an_employee_that_does_not_belong_to_the_company_can_not_view_the_company()
    {

        $authUser = $this->createUser(RoleType::EMPLOYEE);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $user->id]);
        $response = $this->getJson(route('companies.show', $company->id));
        $response->assertStatus(403);
    }

    public function test_an_employee_that_belong_to_the_company_can_view_the_company()
    {
        $authUser = $this->createUser(RoleType::EMPLOYEE);
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $user->id]);
        Employee::factory()->create(['user_id' => $authUser->id, 'company_id' => $company->id]);
        $response = $this->getJson(route('companies.show', $company->id));
        $response->assertStatus(200);
    }
    public function test_a_user_that_owns_the_company_can_view_the_company()
    {
        $authUser = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $authUser->id]);
        $response = $this->getJson(route('companies.show', $company->id));
        $response->assertStatus(200);
    }
    public function test_a_super_admin_can_update_company_profile()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $authUser->id]);
        $response = $this->patchJson(route('companies.update', $company->id), ['name' => 'First central']);
        $response->assertStatus(200);
    }
    public function test_admin_can_not_update_a_company_profile()
    {
        $authUser = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $authUser->id]);
        $response = $this->patchJson(route('companies.update', $company->id), ['name' => 'First central']);
        $response->assertStatus(403);
    }

    public function test_a_super_admin_can_delete_company_profile()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $authUser->id]);
        $response = $this->delete(route('companies.destroy', $company->id));
        $this->assertDatabaseCount('companies', 0);
        $response->assertStatus(200);
    }
    public function test_admin_can_not_delete_a_company_profile()
    {
        $authUser = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($authUser);
        $company = Company::factory()->create(['user_id' => $authUser->id]);
        $response = $this->delete(route('companies.destroy', $company->id));
        $this->assertDatabaseCount('companies', 1);
        $response->assertStatus(403);
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
}
