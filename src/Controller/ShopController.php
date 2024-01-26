<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class ShopController extends Controller
{
    public function default(Request $request, Response $response, array $args): Response
    {
        //trigger_error('Test error message', E_USER_ERROR);
        $query = $this->connection->createQueryBuilder();
        $rows = $query
            ->select('id', 'name', 'type', 'weight', 'aero')
            ->from('bikes')
            ->executeQuery()
            ->fetchAllAssociative() ?: [];

        $bikes = $rows;
        $viewData = [
            'bikes' => $bikes,
        ];

        return $this->twig->render($response, 'default.html.twig', $viewData);
    }

    public function details(Request $request, Response $response, array $args): Response
    {
        $query = $this->connection->createQueryBuilder();
        $rows = $query
            ->select('id', 'name', 'type', 'weight', 'aero')
            ->from('bikes')
            ->executeQuery()
            ->fetchAllAssociative() ?: [];

        $bikes = $rows;
        $key = array_search($args['id'], array_column($bikes, 'id'));

 //       var_dump($key);
//        $viewData = [
////            'bikes' => $bikes,
//            'bike' => $bikes[$key]
//        ];




        if ($key === false) {
            throw new HttpNotFoundException($request);
//            return $this->twig->render($response, 'not-found.html.twig');
        }

//        return $this->twig->render($response, 'details.html.twig', $viewData);



        return $this->twig->render($response, 'details.html.twig', [
            'bike' => $bikes[$key]
        ]);
    }
}
