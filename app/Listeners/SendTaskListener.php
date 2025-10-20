<?php

namespace App\Listeners;

use App\Broker\BrokerInterface;
use App\Broker\BrokerMessageDto;
use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use Illuminate\Support\Facades\Log;

class SendTaskListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly BrokerInterface $broker,
    )
    {
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated|TaskUpdated|TaskDeleted $event): void
    {
        $body = json_encode([
            'event' => $event->getType(),
            'task_id' => $event->task->id,
            'user_id' => $event->task->user_id,
            'timestamp' => now(),
        ]);

        $message = new BrokerMessageDto(
            queue: 'task-events',
            body: $body,
        );

        $this->broker->publish($message);
    }
}
