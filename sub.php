<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.6.55:5672', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('vikings_consumers', true, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->getBody(), "\n";
    sleep(substr_count($msg->getBody(), '.'));
    echo " [x] Done\n";
    $msg->ack();
};

$channel->basic_qos(null, 1, false);
$channel->basic_consume('vikings_consumers', '', false, false, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

// $channel->close();
// $connection->close();