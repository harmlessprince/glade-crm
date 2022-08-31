<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Laravel\Sanctum\Sanctum;
use Mockery\Mock;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;

    /**
     * @return void
     */
    public function test_store_method_binds_user_repository_interface()
    {
        $authUser = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($authUser);
        $user = $this->createUser(RoleType::ADMIN);
        $repository = \Mockery::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->andReturn($user);
       $this->app->instance('App\Repositories\Contracts\UserRepositoryInterface', $repository);
       $response = $this->call('POST', 'api/users', $this->data());
       $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_super_admin_can_create_user_account()
    {
        $this->withExceptionHandling();
        $user = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 2);
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_user_with_role_admin_can_not_create_user_account()
    {
        $user = $this->createUser(RoleType::EMPLOYEE);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_user_with_role_employee_can_not_create_user_account()
    {
        $user = $this->createUser(RoleType::EMPLOYEE);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_user_with_role_company_can_not_create_user_account()
    {
        $user = $this->createUser(RoleType::COMPANY);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(403);
    }

    /**
     * @return void
     * @watch
     */
    public function test_user_with_role_super_admin_can_delete_user_account()
    {
        $user = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($user);
        $newUser = $this->createUser(RoleType::COMPANY);
        $response = $this->deleteJson(route('users.destroy', ['user' => $newUser]));
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(200);
    }

    /**
     * @return void
     * @watch
     */
    public function test_a_none_super_admin_can_not_delete_user_account()
    {
        $user = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($user);
        $newUser = $this->createUser(RoleType::COMPANY);
        $response = $this->deleteJson(route('users.destroy', ['user' => $newUser]));
        $this->assertDatabaseCount('users', 2);
        $response->assertStatus(403);
    }

    /**
     * @return void
     * @watch
     */
    public function test_user_with_role_super_admin_can_update_user_account()
    {
        $user = $this->createUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($user);
        $newUser = $this->createUser(RoleType::COMPANY);
        $response = $this->patchJson(route('users.update', ['user' => $newUser]), ['name' => 'updated name']);
        $this->assertSame(['name' => 'updated name'], ['name' => $response->json()['result']['name']]);
        $this->assertDatabaseCount('users', 2);
        $response->assertStatus(200);
    }

    /**
     * @return void
     * @watch
     */
    public function test_a_none_super_admin_can_not_update_user_account()
    {
        $user = $this->createUser(RoleType::ADMIN);
        Sanctum::actingAs($user);
        $newUser = $this->createUser(RoleType::EMPLOYEE);
        $response = $this->patchJson(route('users.update', ['user' => $newUser]), ['name' => 'updated name']);
        $response->assertStatus(403);
    }

    private function createUser($role)
    {
        return User::factory()->create(['role' => $role]);
    }

    private function data(): array
    {
        return ['name' => 'James Kean', 'email' => 'kames@gmail.com', 'password' => 'password', 'role' => RoleType::ADMIN];
    }
}
