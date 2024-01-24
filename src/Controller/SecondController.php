<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Doctrine\DBAL\Connection;

class SecondController extends Controller
{

    public array $users = [];

    public function second(Request $request, Response $response, array $args): Response
    {

        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select('id', 'username')
            ->from('users')
            ->executeQuery()
            ->fetchAllAssociative() ?: [];

        $users = $rows;
        $viewData = [
            'users' => $users,
        ];

        $this->flash->addMessageNow('errors', 'A flash message from my controller; but sent via session');
        $this->flash->addMessageNow('errors', 'Invalid username or password');

        $messages = $this->flash->getMessages();
        //     var_dump($messages);
        $data = [
            'name' => 'World (World) is sent from First Controller $data variable...',
            'message' => 'Your message sire is here sent from First Controller $data variable...',
//            'errors' => $this->flash,
        ];

        //  $users = array(['name' => 'mark']);
        //$users = ['subscriber' => 'fucking name'];
        // var_dump($users);
        return $this->twig->render($response, 'second.html.twig', $viewData);
    }
}
