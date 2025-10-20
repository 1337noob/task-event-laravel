<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetLogsRequest;
use App\Http\Resources\LogResource;
use App\Services\LogService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class LogController
{
    public function __construct(
        private readonly LogService $logService,
    )
    {
    }

    #[OA\Get(
        path: "/api/logs",
        summary: "Get logs",
        tags: ["Logs"],
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
                name: "user_id",
                description: "Search user_id",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string", example: "0199dd17-88ea-7037-ba2d-cf60e784f928")
            ),
            new OA\Parameter(
                name: "event",
                description: "Search event",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string", example: "created")
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(ref: "#/components/schemas/LogListResponse")
            ),
        ]
    )]
    public function index(GetLogsRequest $request): AnonymousResourceCollection
    {
        $result = $this->logService->getByFilters($request);

        return LogResource::collection($result);
    }
}
