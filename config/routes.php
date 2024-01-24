<?php

// Define app routes


use App\Action\escapeAction;
use App\Action\Hello\HelloAction;
use App\Action\Hello\ParamsAction;
use App\Action\HiAction;
use App\Action\Home\HomeAction;
use App\Controller\FirstController;
use App\Controller\LoopController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeAction::class)->setName('home');

    $app->get('/helloworld', HelloAction::class)->setName('hello'); //Lesson one

    $app->get('/hi/{nameholder}', HiAction::class); //Lesson two

    $app->get('/hello/{name}', ParamsAction::class)->setName('params'); //Lesson two

    $app->get('/first', 'App\Controller\FirstController:homepage');

    $app->get('/second', 'App\Controller\SecondController:second');

    $app->get('/loop', 'App\Controller\LoopController:loop');

    $app->any('/home', 'App\Controller\SearchController:records');

    $app->any('/search', '\App\Controller\SearchController:search');

    $app->any('/form', '\App\Controller\SearchController:form');

    $app->any('/default', '\App\Controller\ShopController:default');

    $app->get('/details/{id:[0-9]+}', '\App\Controller\ShopController:details');


};
