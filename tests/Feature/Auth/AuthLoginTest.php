<?php

namespace Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_login_success(): void
    {
        $this->assertGuest('api');

        $user = User::factory()->create([
            'login' => 'user1',
            'password' => Hash::make('password'),
        ]);

        $creds = [
            'login' => 'user1',
            'password' => 'password',
        ];

        $response = $this->postJson(route('auth.login'), $creds);

        $response->dump()
            ->assertStatus(200);

        $this->assertAuthenticated('api');

        $this->assertAuthenticatedAs($user);
    }

    public function test_auth_login_fail_wrong_password(): void
    {
        $this->assertGuest('api');

        $user = User::factory()->create([
            'login' => 'user1',
            'password' => Hash::make('password'),
        ]);

        $creds = [
            'login' => 'user1',
            'password' => 'wrong',
        ];

        $response = $this->postJson(route('auth.login'), $creds);

        $response->dump()
            ->assertStatus(401);

        $this->assertGuest('api');
    }
}
