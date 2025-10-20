<?php

declare(strict_types=1);

namespace App\Services;

use app\Dto\Logs\LogDto;
use app\Dto\Logs\LogFiltersDto;
use App\Http\Requests\GetLogsRequest;
use App\Repositories\LogRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LogService
{
    public function __construct(
        private readonly LogRepository $logRepository,
        private readonly int $cache_ttl,
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function getByFilters(GetLogsRequest $request): LengthAwarePaginator
    {
        $validated = $request->validated();

        $filters = new LogFiltersDto(
            user_id: $validated['user_id'] ?? null,
            event: $validated['event'] ?? null,
            page: isset($validated['page']) ? (int)$validated['page'] : 1,
            per_page: isset($validated['per_page']) ? (int)$validated['per_page'] : 10,
        );

        $key = sprintf('%s:%s:%s:%s',
            $filters->page,
            $filters->per_page,
            $filters->event,
            $filters->user_id
        );

        try {
            $result = $this->logRepository->getByFilters($filters);

            Cache::set($key, $result, $this->cache_ttl);
        } catch (\Throwable $e) {
            Log::error($e);

            if (!Cache::has($key)) {
                throw $e;
            }

            $result = Cache::get($key);
        }

        return $this->map($result);
    }

    private function map(array $data): LengthAwarePaginator
    {
        $tasks = collect($data['data'])->map(function ($item) {
            return new LogDto(
                id: $item['id'],
                event: $item['event'],
                task_id: $item['task_id'],
                user_id: $item['user_id'],
                created_at: $item['created_at'],
                updated_at: $item['updated_at'],
            );
        });

        return new LengthAwarePaginator(
            items: $tasks,
            total: $data['meta']['total'],
            perPage: $data['meta']['per_page'],
            currentPage: $data['meta']['current_page'],
        );
    }
}
