<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[OA\Post(
        path: "/api/tasks",
        summary: "Create a task",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["title"],
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "New task")
                    ]
                )
            )
        ),
        tags: ["Tasks"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Tasks created",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "data",
                                properties: [
                                    new OA\Property(
                                        property: "task",
                                        properties: [
                                            new OA\Property(property: "id", type: "string", example: "0199e304-798d-72ad-8f41-80bdc6de0d90"),
                                            new OA\Property(property: "title", type: "string", example: "New task"),
                                            new OA\Property(property: "user_id", type: "string", example: "0199dd17-88ea-7037-ba2d-cf60e784f928"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z")
                                        ],
                                        type: "object"
                                    )
                                ],
                                type: "object"
                            )
                        ]
                    )
                )
            ),
        ]
    )]
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
        ]);

        TaskCreated::dispatch($task);

        return response()->json([
            'data' => ['task' => $task]
        ], 201);
    }

    #[OA\Put(
        path: "/api/tasks/{uuid}",
        summary: "Update a task",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["title"],
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Updated")
                    ]
                )
            )
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
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "data",
                                properties: [
                                    new OA\Property(
                                        property: "task",
                                        properties: [
                                            new OA\Property(property: "id", type: "string", example: "0199e304-798d-72ad-8f41-80bdc6de0d90"),
                                            new OA\Property(property: "title", type: "string", example: "Updated"),
                                            new OA\Property(property: "user_id", type: "string", example: "0199dd17-88ea-7037-ba2d-cf60e784f928"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2025-10-14T14:30:15.000000Z")
                                        ],
                                        type: "object"
                                    )
                                ],
                                type: "object"
                            )
                        ]
                    )
                )
            ),
        ]
    )]
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        TaskUpdated::dispatch($task);

        return response()->json([
            'data' => ['task' => $task]
        ], 200);
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
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "data", type: "string", example: "deleted")
                        ]
                    )
                )
            ),
        ]
    )]
    public function destroy(Task $task)
    {
        $task->delete();

        TaskDeleted::dispatch($task);

        return response()->json([
            'data' => 'deleted'
        ], 200);
    }
}
