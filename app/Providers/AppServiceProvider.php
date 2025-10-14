<?php

namespace App\Providers;

use App\Broker\BrokerInterface;
use App\Broker\RabbitMQ\RabbitMQBroker;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RabbitMQBroker::class, function ($app) {
            $connection = config('rabbitmq.connection');

            return new RabbitMQBroker(
                new AMQPStreamConnection(
                    $connection['host'],
                    $connection['port'],
                    $connection['user'],
                    $connection['password'],
                    $connection['vhost'],
                    false, // insist
                    'AMQPLAIN', // login_method
                    null, // login_response
                    'en_US', // locale
                    $connection['connection_timeout'],
                    $connection['read_write_timeout'],
                    null, // context
                    false, // keepalive
                    $connection['heartbeat']
                )
            );
        });
    }

    public $bindings = [
        BrokerInterface::class => RabbitMQBroker::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
