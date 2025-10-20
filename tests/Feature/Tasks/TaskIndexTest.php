<?php

namespace Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_index_with_page_params(): void
    {
        $user = User::factory()->create();

        $total = 21;
        $page = 1;
        $perPage = 20;

        Task::factory($total)->for($user, 'user')->create();

        $this->assertDatabaseCount('tasks', $total);

        $response = $this->actingAs($user)
            ->getJson(route('tasks.index', [
                'page' => $page,
                'per_page' => $perPage,
            ]));

        $response->dump()
            ->assertJsonFragment([
                'current_page' => $page,
                'last_page' => 2,
                'total' => $total,
            ])
            ->assertStatus(200);
    }

    public function test_task_index_with_search_params(): void
    {
        $user = User::factory()->create();

        $search = 'test';
        $searchTitle = 'New Test Title';
        $otherTitle = 'Other Title';

        $task1 = Task::factory([
            'title' => $searchTitle,
        ])->for($user, 'user')->create();
        $task2 = Task::factory([
            'title' => $searchTitle,
        ])->for($user, 'user')->create();
        $task3 = Task::factory([
            'title' => $searchTitle,
        ])->for($user, 'user')->create();
        $task4 = Task::factory([
            'title' => $otherTitle,
        ])->for($user, 'user')->create();

        $response = $this->actingAs($user)
            ->getJson(route('tasks.index', [
                'title' => $search,
            ]));

        $response->dump()
            ->assertJsonFragment([
                'id' => $task1->id,
                'title' => $searchTitle,
            ])
            ->assertJsonFragment([
                'id' => $task2->id,
                'title' => $searchTitle,
            ])
            ->assertJsonFragment([
                'id' => $task3->id,
                'title' => $searchTitle,
            ])
            ->assertJsonMissing([
                'id' => $task4->id,
            ])
            ->assertJsonMissing([
                'title' => $otherTitle,
            ])
            ->assertStatus(200);
    }
}
