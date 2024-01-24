<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class HiAction
{
    private Twig $twig;



    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $viewData = [
            'name' => 'World',
            'nameholder' => $request->getAttribute('nameholder'),
            'notifications' => [
                'message' => 'You are good!'
            ],
        ];


        return $this->twig->render($response, 'hello.twig', $viewData);
    }
}
