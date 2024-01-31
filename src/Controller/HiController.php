<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

final class HiController
{
    protected Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $name = ucfirst($args['name']);

/*
 * I added this object array tasks below to figure out Twig templating for hi.html.twig.
 * It uses the class App\Controller\Tasks.php which does not need a use statement
 * as both classes HiController and Tasks are in the same namespace.
 */
        $tasks = [
            new Tasks(
                1,
                'Buy groceries',
                'Tasks 1 description',
                'Tasks 1 long description',
                false,
                '2023-03-01 12:00:00',
                '2023-03-01 12:00:00'
            ),
            new Tasks(
                2,
                'Sell old stuff',
                'Tasks 2 description',
                null,
                false,
                '2023-03-02 12:00:00',
                '2023-03-02 12:00:00'
            ),
            new Tasks(
                3,
                'Learn programming',
                'Tasks 3 description',
                'Tasks 3 long description',
                true,
                '2023-03-03 12:00:00',
                '2023-03-03 12:00:00'
            ),
            new Tasks(
                4,
                'Take dogs for a walk',
                'Tasks 4 description',
                null,
                false,
                '2023-03-04 12:00:00',
                '2023-03-04 12:00:00'
            ),
        ];


//        $tasks = json_decode(file_get_contents(__DIR__ . '/../../data/albums.json'), true);

    //    public function __invoke(
    //        ServerRequestInterface $request,
    //        ResponseInterface $response,
    //    ): ResponseInterface {
        $viewData = [
            'name' => $name,
            'tasks' => $tasks,
            'nameholder' => $request->getAttribute('nameholder'),
            'notifications' => [
                'message' => 'You are good!',
                'messagetwo' => 'You are not good',
            ],
        ];
        return $this->twig->render($response, 'hi.html.twig', $viewData);
    }
}
