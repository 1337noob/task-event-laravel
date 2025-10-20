<?php

namespace Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_logout_success(): void
    {
        $this->assertGuest('api');

        $user = User::factory()->create([
            'login' => 'user1',
        ]);

        Auth::guard('api')->login($user);

        $this->assertAuthenticated('api');

        $this->assertAuthenticatedAs($user);

        $response = $this->postJson(route('auth.logout'));

        $response->dump()
            ->assertStatus(204);

        $this->assertGuest('api');
    }
}
