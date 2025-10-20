<?php

declare(strict_types=1);

namespace App\Repositories;

use app\Dto\Tasks\CreateTaskDto;
use app\Dto\Tasks\FiltersDto;
use app\Dto\Tasks\UpdateTaskDto;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository
{
    public function getByFilters(FiltersDto $filters): LengthAwarePaginator
    {
        $query = Task::query()
            ->where('user_id', $filters->user_id);

        if ($filters->title) {
            $query->where('title', 'like', '%' . $filters->title . '%');
        }

        return $query->paginate($filters->per_page);
    }

    public function findById(string $id): ?Task
    {
        return Task::query()
            ->with('user')
            ->find($id);
    }

    public function create(CreateTaskDto $createTask): Task
    {
        return Task::query()->create([
            'title' => $createTask->title,
            'user_id' => $createTask->user_id,
        ]);
    }

    public function update(UpdateTaskDto $updateTask): Task
    {
        $task = Task::query()->findOrFail($updateTask->id);

        $task->update([
            'title' => $updateTask->title,
        ]);

        return $task;
    }

    public function delete(string $id): void
    {
        Task::destroy($id);
    }
}
