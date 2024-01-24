<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

//print_r(value: $_POST['q']);



class SearchController extends Controller
{
    public function search(Request $request, Response $response, array $args): Response
    {
        $query = $this->connection->createQueryBuilder();

        if (! isset($_POST['q'])) {
            $artist = '';
        } else {
            print_r($_POST['q']);
            $artist = ($_POST['q']);
        }

        $rows = $query
            ->select('id', 'title', 'artist')
            ->from('records')
            ->where('artist LIKE :artist')
            ->setParameter('artist', '%' . $artist . '%')
            ->executeQuery()
            ->fetchAllAssociative() ?: [];

        $records = $rows;
        $viewData = [
            'records' => $records,
        ];

        return $this->twig->render($response, 'search.html.twig', $viewData);
    }

    public function form(Request $request, Response $response, array $args): Response
    {
        $query = $this->connection->createQueryBuilder();

        if (! isset($_POST['q'])) {
            $artist = '';
        } else {
            print_r($_POST['q']);
            $artist = ($_POST['q']);
        }

        $rows = $query
            ->select('id', 'title', 'artist')
            ->from('records')
            ->where('artist LIKE :artist')
            ->setParameter('artist', '%' . $artist . '%')
            ->executeQuery()
            ->fetchAllAssociative() ?: [];

        $records = $rows;
        $viewData = [
            'records' => $records,
        ];

        return $this->twig->render($response, 'form.html.twig', $viewData);
    }

    public function records(Request $request, Response $response, array $args): Response
    {

        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select('id', 'title', 'artist')
            ->from('records')
            ->executeQuery()
            ->fetchAllAssociative() ?: [];

        $records = $rows;
        $viewData = [
            'records' => $records,
        ];

        return $this->twig->render($response, 'records.html.twig', $viewData);
    }
}
