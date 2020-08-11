<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class Container
 */
class Container
{
    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * Container constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        if (null === $this->logger) {
            try {
                $this->logger = new Logger('main');
                $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
            } catch (\Exception $exception) {
                echo 'Failed to init logger!' . PHP_EOL . $exception;
                error_log('Failed to init logger!' . PHP_EOL . $exception);
            }
        }

        return $this->logger;
    }
}