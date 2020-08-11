<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/health', function (Request $request, Response $response) use ($app) {
        $response = (new \IotMediaApi\Controller\HealthController($app->getContainer()->get(\App\Container::class)))->get($request, $response, []);
        return $response->withHeader('Content-Type', 'application/json');
    });
};
