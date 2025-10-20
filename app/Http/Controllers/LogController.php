<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetLogsRequest;
use App\Http\Resources\LogResource;
use App\Services\LogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LogController
{
    public function __construct(
        private readonly LogService $logService,
    )
    {
    }

    public function index(GetLogsRequest $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $result = $this->logService->getByFilters($request);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()],
                $e->getCode() ?? 500
            );
        }

        return LogResource::collection($result);
    }
}
