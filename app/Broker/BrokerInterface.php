<?php

namespace App\Broker;

interface BrokerInterface
{
    public function publish(BrokerMessageDto $message): bool;
}
