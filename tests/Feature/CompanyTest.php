<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\Company;
use App\Models\User;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $repository->shouldReceive('all')->once()->andReturn(Company::all());
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
     * A basic feature test example.
     *
     * @return void
     */
    public function example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    private function createUser($role)
    {
        return User::factory()->create(['role' => $role]);
    }
}
