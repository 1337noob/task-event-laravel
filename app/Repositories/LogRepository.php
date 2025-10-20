<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Logs\LogFiltersDto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class LogRepository
{
    public function __construct(
        private string $base_url,
    )
    {
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function getByFilters(LogFiltersDto $filters): array
    {
        $params['page'] = $filters->page;
        $params['per_page'] = $filters->per_page;

        if ($filters->event) {
            $params['event'] = $filters->event;
        }
        if ($filters->user_id) {
            $params['user_id'] = $filters->user_id;
        }

        $response = Http::acceptJson()
            ->get($this->base_url . '/api/logs', $params);

        $result = $response->json();

        $this->guardResponse($result);

        return $result;
    }

    /**
     * @throws \Exception
     */
    private function guardResponse(array $result): void
    {
        if (
            !isset($result['data'])
            || !isset($result['meta'])
            || !isset($result['meta']['total'])
            || !isset($result['meta']['per_page'])
            || !isset($result['meta']['current_page'])
        ) {
            throw new \Exception(
                'invalid response structure'
            );
        }
    }
}
