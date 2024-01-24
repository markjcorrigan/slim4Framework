<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SessionMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Check if a session is already started
        if (session_status() === PHP_SESSION_NONE) {
            // Start the session if not already started
            session_start();
        }

        // Continue processing the request and response
        return $handler->handle($request);
    }
}