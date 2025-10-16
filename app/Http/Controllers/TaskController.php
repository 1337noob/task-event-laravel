<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Http\Requests\GetTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[OA\Get(
        path: "/api/tasks",
        summary: "Get tasks",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "page",
                description: "Page number",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "per_page",
                description: "Per page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: 10)
            ),
            new OA\Parameter(
                name: "search",
                description: "Search title",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string", example: "new")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(ref: "#/components/schemas/TaskListResponse")
            ),
        ]
    )]
    public function index(GetTasksRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 10;
        $page = $validated['page'] ?? 1;
        $search = $validated['search'] ?? null;

        $query = $request->user()->tasks();

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $query->orderBy('created_at', 'DESC');

        $tasks = $query->paginate($perPage, ['*'], 'page', $page);

        return TaskResource::collection($tasks);
    }

    #[OA\Post(
        path: "/api/tasks",
        summary: "Create a task",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreTaskRequest")
        ),
        tags: ["Tasks"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent(ref: "#/components/schemas/TaskResponse")
            ),
        ],
    )]
    public function store(StoreTaskRequest $request): TaskResource
    {
        $validated = $request->validated();

        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
        ]);

        TaskCreated::dispatch($task);

        return new TaskResource($task);
    }

    #[OA\Get(
        path: "/api/tasks/{uuid}",
        summary: "Show a task",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "uuid",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", format: "uuid")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(ref: "#/components/schemas/TaskResponse")
            ),
        ],
    )]
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    #[OA\Put(
        path: "/api/tasks/{uuid}",
        summary: "Update a task",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateTaskRequest")
        ),
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "uuid",
                description: "UUID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", format: "uuid")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Updated",
                content: new OA\JsonContent(ref: "#/components/schemas/TaskResponse")
            ),
        ]
    )]
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $validated = $request->validated();

        $task->update($validated);

        TaskUpdated::dispatch($task);

        return new TaskResource($task);
    }

    #[OA\Delete(
        path: "/api/tasks/{uuid}",
        summary: "Delete a task",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "uuid",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", format: "uuid")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted",
            ),
        ]
    )]
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        TaskDeleted::dispatch($task);

        return response()->json([
            'data' => 'deleted'
        ], 200);
    }
}
