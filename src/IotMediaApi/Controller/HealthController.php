<?php

declare(strict_types=1);

namespace IotMediaApi\Controller;

use App\Container;
use IotMediaApi\NatService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class HealthController, serving GET /health, GET /ready requests.
 *
 * @package App
 */
class HealthController
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
     * Serving GET /health.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \JsonException
     */
    public function getHealth(Request $request, Response $response, array $args): Response
    {
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode(['status' => 'okay'], JSON_THROW_ON_ERROR, 512));

        return $response;
    }

    /**
     * Serving GET /ready.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \JsonException
     */
    public function getReady(Request $request, Response $response, array $args): Response
    {
        if ((new NatService($this->container))->isReachable()) {
            $response = $response->withStatus(200);
            $response->getBody()->write(json_encode(['status' => 'okay'], JSON_THROW_ON_ERROR, 512));
        } else {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(['status' => 'fail'], JSON_THROW_ON_ERROR, 512));
        }

        return $response;
    }
}
