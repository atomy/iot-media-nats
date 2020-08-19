<?php

declare(strict_types=1);

namespace IotMediaApi\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class MetaController, serving GET /meta requests.
 *
 * @package App
 */
class MetaController
{
    /**
     * Serving GET /health.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \JsonException
     */
    public function get(Request $request, Response $response, array $args)
    {
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode(['version' => $this->getVersion()], JSON_THROW_ON_ERROR, 512));

        return $response;
    }

    /**
     * Read and return current deployed version from file.
     *
     * @return string version of application
     */
    protected function getVersion(): string
    {
        $filePath = APP_ROOT . '/current_version';

        if (is_file($filePath) && is_readable($filePath)) {
            return trim(file_get_contents($filePath));
        }

        return '?';
    }
}
