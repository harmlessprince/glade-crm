<?php

namespace Tests\Feature;

use App\Constants\RoleType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, DatabaseMigrations;
    /**
     * A basic feature test example.
     * @watch
     * @return void
     */
    public function test_user_can_not_login_with_invalid_credentials()
    {
        $user = $this->getUser();
        $response  = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password1',
        ]);
        $response->assertStatus(401);
        $this->assertFalse(auth('sanctum')->check());
    }

    /**
     *
     * @watch
     * @return void
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = $this->getUser();
        $response  = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $this->assertTrue(auth('sanctum')->check());
    }

    private function  getUser(){
        return User::factory()->create(['role' => RoleType::SUPER_ADMIN]);
    }
}
