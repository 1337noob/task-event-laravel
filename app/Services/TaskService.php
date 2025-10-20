<?php

declare(strict_types=1);

namespace App\Services;

use app\Dto\Tasks\CreateTaskDto;
use app\Dto\Tasks\FiltersDto;
use app\Dto\Tasks\UpdateTaskDto;
use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Http\Requests\GetTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\ShowTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository
    )
    {
    }

    public function getByFilters(GetTasksRequest $request): LengthAwarePaginator
    {
        $validated = $request->validated();

        $page = isset($validated['page'])
            ? (int)$validated['page']
            : 1;
        $perPage = isset($validated['per_page'])
            ? (int)$validated['per_page']
            : config('paginate.per_page');

        return $this->taskRepository->getByFilters(new FiltersDto(
            user_id: $request->user()->id,
            title: $validated['title'] ?? null,
            page: $page,
            per_page: $perPage,
        ));
    }

    public function create(StoreTaskRequest $request): Task
    {
        $validated = $request->validated();

        $task = $this->taskRepository->create(new CreateTaskDto(
            title: $validated['title'],
            user_id: $request->user()->id,
        ));

        TaskCreated::dispatch($task);

        return $task;
    }

    public function findById(ShowTaskRequest $request): Task
    {
        $validated = $request->validated();

        $task = $this->taskRepository->findById($validated['id']);

        $this->guardTaskUserId($task, $request->user()->id);

        return $task;
    }

    public function update(UpdateTaskRequest $request): Task
    {
        $validated = $request->validated();

        $task = $this->taskRepository->findById($validated['id']);

        $this->guardTaskUserId($task, $request->user()->id);

        $task = $this->taskRepository->update(new UpdateTaskDto(
            id: $validated['id'],
            title: $validated['title'],
        ));

        TaskUpdated::dispatch($task);

        return $task;
    }

    public function deleteById(DeleteTaskRequest $request): void
    {
        $validated = $request->validated();

        $task = $this->taskRepository->findById($validated['id']);

        $this->guardTaskUserId($task, $request->user()->id);

        $this->taskRepository->delete($validated['id']);

        TaskDeleted::dispatch($task);
    }

    private function guardTaskUserId(Task $task, string $userId): void
    {
        if ($task->user->id !== $userId) {
            throw new AccessDeniedHttpException(
                'Unauthorized'
            );
        }
    }
}
