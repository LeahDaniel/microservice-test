<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.6.55:5672', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare(
    exchange: $exchange = 'vikings_fanout', 
    type: 'fanout', 
    passive: true,
    durable: false, 
    auto_delete: false
);

[$queue_name] = $channel->queue_declare(
    passive: false, 
    durable: false, 
    exclusive: true, 
    auto_delete: true);

$channel->queue_bind($queue_name, $exchange);

echo " [*] Waiting for logs. To exit press CTRL+C\n";

echo $queue_name;

$callback = function ($msg) {
    echo ' [x] ', $msg->getBody(), "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

$channel->close();
$connection->close();