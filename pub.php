<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('192.168.6.55:5672', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('vikings_consumers', false, false, false, true);

$msg = new AMQPMessage('Testing Leah');
$channel->basic_publish($msg, '', 'vikings_consumers');