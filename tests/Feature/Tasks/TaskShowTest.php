<?php

namespace Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_show(): void
    {
        $user = User::factory()->create();

        $task = Task::factory([
            'title' => 'Show task',
        ])->for($user, 'user')->create();

        $response = $this->actingAs($user)
            ->getJson(route('tasks.show', [$task]));

        $response->dump()
            ->assertJsonFragment([
                'id' => $task->id,
                'title' => $task->title,
            ])
            ->assertStatus(200);
    }
}
