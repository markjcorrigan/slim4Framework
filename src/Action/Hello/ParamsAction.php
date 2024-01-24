<?php

namespace App\Action\Hello;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ParamsAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $name = ucfirst($args['name']);
        $response->getBody()->write('Hello, ' . $name);

        return $response;
    }
}