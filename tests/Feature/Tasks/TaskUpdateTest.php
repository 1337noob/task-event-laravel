<?php

namespace Feature\Tasks;

use App\Events\TaskUpdated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_update(): void
    {
        Event::fake([
            TaskUpdated::class,
        ]);

        $user = User::factory()->create();

        $task = Task::factory()
            ->for($user, 'user')->create();

        $updatedTask = [
           'title' => 'Updated Task',
        ];

        $response = $this->actingAs($user)
            ->putJson(
                route('tasks.update', [$task]),
                $updatedTask
            );

        $response
            ->dump()
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $updatedTask['title'],
        ]);

        Event::assertDispatched(TaskUpdated::class);
    }
}
