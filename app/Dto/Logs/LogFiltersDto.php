<?php

declare(strict_types=1);

namespace app\Dto\Logs;

readonly class LogFiltersDto
{
    public function __construct(
        public ?string $user_id,
        public ?string $event,
        public int $page,
        public int $per_page,
    )
    {
    }
}
