<?php

declare(strict_types=1);

namespace App\Broker;

interface BrokerInterface
{
    public function publish(BrokerMessageDto $message): bool;
}
