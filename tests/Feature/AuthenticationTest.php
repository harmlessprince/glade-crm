<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use  RefreshDatabase, WithFaker, DatabaseMigrations;

    /**
     * A basic feature test example.
     * @return void
     */
    public function test_user_can_not_login_with_invalid_credentials()
    {
        $user = $this->getUser();
        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password1',
        ]);
        $response->assertStatus(401);
        $this->assertFalse(auth('sanctum')->check());
    }

    /**
     *
     * @return void
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = $this->getUser();
        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $this->assertTrue(auth('sanctum')->check());
    }

    /**
     *
     * @return void
     */
    public function test_user_can_logout_by_supplying_token()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);
        $response = $this->postJson('api/auth/logout');
        $response->assertStatus(200);
    }

    /**
     * An unauthenticated user can not access protected routes
     * @return void
     */
    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $this->withoutExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->postJson('/api/auth/logout'); //This route is protected with auth:sanctum middleware
    }

    private function getUser()
    {
        return User::factory()->create(['role' => RoleType::SUPER_ADMIN]);
    }
}
