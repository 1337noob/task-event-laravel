<?php

declare(strict_types=1);

namespace App\Dto\Tasks;

readonly class FiltersDto
{
    public function __construct(
        public string $user_id,
        public ?string $title,
        public int $page,
        public int $per_page,
    )
    {
    }
}
