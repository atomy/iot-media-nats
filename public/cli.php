<?php
declare(strict_types=1);

use App\Handler\HttpErrorHandler;
use App\Handler\ShutdownHandler;
use App\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$connectionOptions = new \Nats\ConnectionOptions();
$connectionOptions->setHost('iot-media-api-nats')->setPort(4222);

$connection = new \Nats\Connection($connectionOptions);
$connection->connect();

// Publish
$connection->subscribe(
    'iot.incomming',
    function ($message) {
        printf("Data: %s\r\n", $message->getBody());
    }
);

while (true) {
    echo 'WAIT...' . PHP_EOL;
    $connection->wait(1);
}
