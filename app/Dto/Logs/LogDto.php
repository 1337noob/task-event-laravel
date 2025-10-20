<?php

declare(strict_types=1);

namespace App\Dto\Logs;

class LogDto
{
    public function __construct(
        public string $id,
        public string $event,
        public string $task_id,
        public string $user_id,
        public string $created_at,
        public string $updated_at,
    )
    {
    }
}
