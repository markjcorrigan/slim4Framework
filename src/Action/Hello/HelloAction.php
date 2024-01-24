<?php

namespace App\Action\Hello;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HelloAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write('Hello, World!');

        return $response;
    }
}
