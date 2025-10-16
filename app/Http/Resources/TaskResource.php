<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TaskListResponse",
    properties: [
        new OA\Property(
            property: "data",
            properties: [
                new OA\Property(
                    property: "tasks",
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Task")
                )
            ],
            type: "object"
        ),
        new OA\Property(
            property: "meta",
            properties: [
                new OA\Property(property: "current_page", type: "integer", example: 1),
                new OA\Property(property: "per_page", type: "integer", example: 10),
                new OA\Property(property: "total", type: "integer", example: 25),
                new OA\Property(property: "last_page", type: "integer", example: 3),
                new OA\Property(property: "from", type: "integer", example: 1),
                new OA\Property(property: "to", type: "integer", example: 10)
            ],
            type: "object"
        ),
        new OA\Property(
            property: "links",
            properties: [
                new OA\Property(property: "first", type: "string", example: "http://localhost:8000/api/tasks?page=1"),
                new OA\Property(property: "last", type: "string", example: "http://localhost:8000/api/tasks?page=3"),
                new OA\Property(property: "prev", type: "string", nullable: true),
                new OA\Property(property: "next", type: "string", example: "http://localhost:8000/api/tasks?page=2")
            ],
            type: "object"
        )
    ]
)]

#[OA\Schema(
    schema: "TaskResponse",
    properties: [
        new OA\Property(
            property: "data",
            properties: [
                new OA\Property(
                    property: "task",
                    ref: "#/components/schemas/Task"
                )
            ],
            type: "object"
        )
    ]
)]

#[OA\Schema(
    schema: "Task",
    properties: [
        new OA\Property(property: "id", type: "string", example: "0199e304-798d-72ad-8f41-80bdc6de0d90"),
        new OA\Property(property: "title", type: "string", example: "New Task"),
        new OA\Property(property: "user_id", type: "string", example: "0199dd17-88ea-7037-ba2d-cf60e784f928"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z")
    ]
)]
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
