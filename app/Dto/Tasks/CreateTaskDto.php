<?php

declare(strict_types=1);

namespace app\Dto\Tasks;

readonly class CreateTaskDto
{
    public function __construct(
        public string $title,
        public string $user_id,
    )
    {
    }
}
