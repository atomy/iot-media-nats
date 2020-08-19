<?php

declare(strict_types=1);

namespace IotMediaApi;

use App\Container;
use App\EnvHelper;

/**
 * Class IotMediaApp.
 *
 * @package IotMediaApp
 */
class NatService
{
    /**
     * @var Container
     */
    protected Container $container;

    /**
     * EventController constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Publish event on NATs.
     *
     * @param array $data input data
     * @throws \JsonException
     * @throws \Exception
     */
    public function sendEvent(array $data): void
    {
        $connectionOptions = new \Nats\ConnectionOptions();
        $connectionOptions->setHost(EnvHelper::get('NATS_HOST'))->setPort((int) EnvHelper::get('NATS_PORT'));
        $connectionOptions->setUser(EnvHelper::get('NATS_USER'))->setPass(EnvHelper::get('NATS_PASSWORD'));

        // connect and publish message
        $connection = new \Nats\Connection($connectionOptions);
        $this->container->getLogger()->info(sprintf(
            "Connecting to NATS-backend on host: '%s:%d' (using username: '%s')",
            $connectionOptions->getHost(),
            (int) $connectionOptions->getPort(),
            $connectionOptions->getUser()
        ));
        $connection->connect();
        $connection->publish('iot.meta', json_encode($data, JSON_THROW_ON_ERROR, 512));

        try {
            $connection->close();
        } catch (\Exception $e) {
            throw new \RuntimeException('failed to close connection!');
        }
    }
}
