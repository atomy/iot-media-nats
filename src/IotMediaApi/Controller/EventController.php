<?php

declare(strict_types=1);

namespace IotMediaApi\Controller;

use App\Container;
use IotMediaApi\EventValidator;
use IotMediaApi\NatService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class EventController, handling POST /event requests.
 *
 * @package App
 */
class EventController
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
     * Serving POST /event.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \JsonException
     */
    public function postEvent(Request $request, Response $response, array $args): Response
    {
        $bodyContent = $request->getBody()->getContents();

        try {
            if (!(new EventValidator($bodyContent))->isValid()) {
                $response = $response->withStatus(400);
                $response->getBody()->write(json_encode(['error' => [['message' => 'supplied json-body is invalid!']]], JSON_THROW_ON_ERROR, 512));
                return $response;
            }

            $bodyContent = json_decode($bodyContent, true, 512, JSON_THROW_ON_ERROR);
            (new NatService($this->container))->sendEvent($bodyContent);
        } catch (\Exception $exception) {
            $this->container->getLogger()->error($exception);
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(['error' => [['message' => $exception->getMessage()]]], JSON_THROW_ON_ERROR, 512));
            return $response;
        }

        $this->container->getLogger()->info('done');

        $response->getBody()->write(json_encode(['message' => 'Message published'], JSON_THROW_ON_ERROR, 512));
        return $response->withStatus(200);
    }
}
