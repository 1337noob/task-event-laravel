<?php

namespace Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthMeTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_me_success(): void
    {
        $user1 = User::factory()->create();

        $response = $this
            ->actingAs($user1)
            ->getJson(route('auth.me'));

        $response->dump()
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user1->id,
                'name' => $user1->name,
                'login' => $user1->login,
            ]);
    }
}
