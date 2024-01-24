<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Flash\Messages;

final class FlashMessagesMiddleware implements MiddlewareInterface
{
    public Messages $flash;

    public function __construct(Messages $flash)
    {
        $this->flash = $flash;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Change flash message storage
        $this->messages->__construct($_SESSION);

        return $handler->handle($request);
    }
}