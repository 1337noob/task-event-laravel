<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService,
    )
    {
    }

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
        $tasks = $this->taskService->getByFilters($request);

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
        $task = $this->taskService->create($request);

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
    public function show(Request $request): TaskResource
    {
        $task = $this->taskService->findById($request);

        return new TaskResource($task);
    }

    #[OA\Put(
        path: "/api/tasks/{id}",
        summary: "Update a task",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateTaskRequest")
        ),
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "id",
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
    public function update(UpdateTaskRequest $request, string $id)
    {
        try {
            $task = $this->taskService->update($request, $id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

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
    public function destroy(Request $request, string $id): JsonResponse
    {
        $this->taskService->deleteById($request, $id);

        return response()->json([
            'data' => 'deleted'
        ], 200);
    }
}
