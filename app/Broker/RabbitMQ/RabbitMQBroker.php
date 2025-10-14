<?php

namespace App\Broker\RabbitMQ;

use App\Broker\BrokerInterface;
use App\Broker\BrokerMessageDto;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQBroker implements BrokerInterface
{
    private AMQPStreamConnection $connection;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    public function publish(BrokerMessageDto $message): bool
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($message->queue, false, false, false, false);

        $msg = new AMQPMessage($message->body);

        $channel->basic_publish($msg, '', $message->queue);

        return true;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
