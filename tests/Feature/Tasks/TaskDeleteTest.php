<?php

namespace Feature\Tasks;

use App\Events\TaskDeleted;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_update(): void
    {
        Event::fake([
            TaskDeleted::class,
        ]);

        $user = User::factory()->create();

        $task = Task::factory()
            ->for($user, 'user')->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('tasks.delete',[$task]));

        $response
            ->dump()
            ->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);

        Event::assertDispatched(TaskDeleted::class);
    }
}
