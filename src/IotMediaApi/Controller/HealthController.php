<?php

declare(strict_types=1);

namespace IotMediaApi\Controller;

use App\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class HealthController
 *
 * @package App
 */
class HealthController
{
    /**
     * @var Container
     */
    private $container;

    /**
     * ButtonController constructor.
     *
     * @param Container $container
     */
//    public function __construct(Container $container)
//    {
//        $this->container = $container;
//    }

    /**
     * Serving GET /button.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function get(Request $request, Response $response, array $args)
    {
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode(['status' => 'okay'], JSON_THROW_ON_ERROR, 512));

        return $response;
    }
}
