<?php

namespace App\Action\User;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserAction
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $username = $this->session->get('user');

        $response->getBody()->write(sprintf('Welcome in %s', $username));

        return $response;
    }
}