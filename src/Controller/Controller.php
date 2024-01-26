<?php

namespace App\Controller;

use App\Middleware\FlashMessagesMiddleware;
use App\Middleware\SessionMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Doctrine\DBAL\Connection;

abstract class Controller
{
    protected Twig $twig;
    protected Messages $flash;
    protected Connection $connection;
    protected ContainerInterface $ci;


    public function __construct(Twig $twig, Messages $flash, Connection $connection, ContainerInterface $ci)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->connection = $connection;
        $this->ci = $ci;
    }

}















