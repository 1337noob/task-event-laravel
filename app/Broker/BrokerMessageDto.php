<?php

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
