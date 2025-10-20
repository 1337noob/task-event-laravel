<?php

declare(strict_types=1);

namespace App\Broker;

readonly class BrokerMessageDto
{
    public function __construct(
        public string $queue,
        public string $body,
    )
    {
    }
}
