<?php

namespace Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_register_success(): void
    {
        $newUser = [
            'name' => 'New User 1',
            'login' => 'user1',
            'password' => 'password',
        ];

        $response = $this->post(route('auth.register'), $newUser);

        $response->dump()
            ->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => $newUser['name'],
            'login' => $newUser['login'],
        ]);
    }

    public function test_auth_register_duplicate_login_fail(): void
    {
        $user = User::factory()->create([
            'login' => 'user1',
        ]);

        $newUser = [
            'name' => 'New User 1',
            'login' => 'user1',
            'password' => 'password',
        ];

        $response = $this->postJson(route('auth.register'), $newUser);

        $response->dump()
            ->assertStatus(422);

    }
}
