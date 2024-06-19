#!/usr/bin/env php
<?php

require_once __DIR__ . "/vendor/autoload.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

const C = 299_792_458;
const AU = 149_597_870_700;
const EARTH = 'earth_control';
const EARTH_IP = '192.168.6.55';
const EARTH_PORT = 5672;
const STATION = 'Leah';

enum Op: string
{
    case JOIN = 'join';
    case LEAVE = 'leave';
    case PING = 'ping';
}

$connection = new AMQPStreamConnection(
    host: EARTH_IP,
    port: EARTH_PORT,
    user: 'guest',
    password: 'guest',
);

$channel = $connection->channel();

$channel->queue_declare(
    queue: STATION,
    passive: false,
    durable: false,
    exclusive: true,
    auto_delete: true,
);

$channel->basic_publish(
    msg: new AMQPMessage(json_encode([
        'op' => Op::JOIN,
        'station' => STATION,
    ])),
    routing_key: EARTH
);

$x = 0;
$y = 0;
$z = 0;
$time = 0;

$channel->basic_consume(
    queue: STATION,
    no_ack: false,
    exclusive: true,
    callback: function (AMQPMessage $msg) use ($channel, &$x, &$y, &$z, &$time) {
        echo $msg->getBody() . PHP_EOL;

        $msg->ack();

        $decoded = json_decode($msg->getBody(), true);

        if (isset($decoded['x'])) {
            $x = $decoded['x'];
            $y = $decoded['y'];
            $z = $decoded['z'];
            echo('x ' . $x . PHP_EOL);
            echo('y ' . $y . PHP_EOL);
            echo('z ' . $z . PHP_EOL);
        }else if (isset($decoded['time'])){
            $time = $decoded['time'];
            echo('received time '. $time . PHP_EOL);
        }

        $x2 = $x * $x;
        $y2 = $y * $y;
        $z2 = $z * $z;

        $sum = $x2 + $y2 + $z2;

        $sqrtSum = sqrt($sum);

        $dividedBySpeedOfLight = $sqrtSum / C;

        $total = $dividedBySpeedOfLight + $time;

        echo('total ' . $total . PHP_EOL);

        $channel->basic_publish(
            msg: new AMQPMessage(json_encode([
                'op' => Op::PING,
                'station' => STATION,
                'time' => $total
            ])),
            routing_key: EARTH
        );
    }
);



try {
    $channel->consume();
} catch (\Throwable $th) {
    echo trim($th->getMessage()) . PHP_EOL;
}

$channel->close();
$connection->close();




