<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UserResponse",
    properties: [
        new OA\Property(
            property: "data",
            properties: [
                new OA\Property(
                    property: "user",
                    ref: "#/components/schemas/User"
                )
            ],
            type: "object"
        )
    ]
)]

#[OA\Schema(
    schema: "User",
    properties: [
        new OA\Property(property: "id", type: "string", example: "0199e304-798d-72ad-8f41-80bdc6de0d90"),
        new OA\Property(property: "name", type: "string", example: "Name"),
        new OA\Property(property: "login", type: "string", example: "user1"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2025-10-14T13:59:05.000000Z")
    ]
)]
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'login' => $this->login,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
