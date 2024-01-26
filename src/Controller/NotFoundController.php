<?php

namespace App\Controller;

use App\Middleware\FlashMessagesMiddleware;
use App\Middleware\SessionMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;

class NotFoundController
{

    private Twig $twig;
    private Messages $flash;

    public function __construct(Twig $twig, Messages $flash)
    {
        $this->twig = $twig;
        $this->flash = $flash;
    }


    public function notfound(Request $request, Response $response): Response
    {
//        trigger_error('Test error message', E_USER_ERROR);
        //session_start();

        $this->flash->addMessageNow('errors', 'A flash message from my controller; but sent via session');
        $this->flash->addMessageNow('errors', 'Invalid username or password');

//        $messages = $this->flash->getMessages();
//        var_dump($messages);
        $data = [
            'name' => 'World (World) is sent from First Controller $data variable...',
            'message' => 'Your message sire is here sent from First Controller $data variable...',
//            'errors' => $this->flash,
        ];

        return $this->twig->render($response, 'not-found.html.twig', $data);
    }
}
