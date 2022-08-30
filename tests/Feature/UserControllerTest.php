<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;
    /**
     * A basic feature test example.
     * @watch
     * @return void
     */
    public function test_super_admin_can_create_user_account()
    {
        $this->withExceptionHandling();
        $user = $this->getUser(RoleType::SUPER_ADMIN);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users',$this->data());
        $this->assertDatabaseCount('users', 2);
        $response->assertStatus(200);
    }
    /**
     * A basic feature test example.
     * @watch
     * @return void
     */
    public function test_user_with_role_admin_can_not_create_user_account()
    {
        $user = $this->getUser(RoleType::EMPLOYEE);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(403);
    }
    /**
     * A basic feature test example.
     * @watch
     * @return void
     */
    public function test_user_with_role_employee_can_not_create_user_account()
    {
        $user = $this->getUser(RoleType::EMPLOYEE);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(403);
    }
    /**
     * A basic feature test example.
     * @watch
     * @return void
     */
    public function test_user_with_role_company_can_not_create_user_account()
    {
        $user = $this->getUser(RoleType::COMPANY);
        Sanctum::actingAs($user);
        $response = $this->postJson('api/users', $this->data());
        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(403);
    }

    private function getUser($role)
    {
        return User::factory()->create(['role' => $role]);
    }

    private function  data(): array{
        return ['name' => 'James Kean', 'email' => 'kames@gmail.com', 'password' => 'password', 'role' => RoleType::ADMIN];
    }
}
