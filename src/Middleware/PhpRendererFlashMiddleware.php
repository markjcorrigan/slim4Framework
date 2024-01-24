<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;

final class PhpRendererFlashMiddleware implements MiddlewareInterface
{
    public Messages $flash;
    public PhpRenderer $phpRenderer;

    public function __construct(Messages $flash, PhpRenderer $phpRenderer)
    {
        $this->flash = $messages;
        $this->phpRenderer = $phpRenderer;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Register php template variable
        $this->phpRenderer->addAttribute('flash', $this->flash);

        return $handler->handle($request);
    }
}