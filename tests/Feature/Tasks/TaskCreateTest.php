<?php

namespace Feature\Tasks;

use App\Events\TaskCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task(): void
    {
        Event::fake([
            TaskCreated::class,
        ]);

        $user = User::factory()->create();

        $newTask = [
           'title' => 'New Task 1',
        ];

        $response = $this->actingAs($user)
            ->post(route('tasks.create'), $newTask);

        $response
            ->dump()
            ->assertStatus(201);

        $this->assertDatabaseHas('tasks', $newTask);

        Event::assertDispatched(TaskCreated::class);
    }
}
