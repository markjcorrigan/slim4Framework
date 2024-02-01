<?php

// Define app routes


use App\Action\escapeAction;
use App\Action\Hello\HelloAction;
use App\Action\Hello\ParamsAction;
use App\Action\HiAction;
use App\Action\Home\HomeAction;
use App\Controller\FirstController;
use App\Controller\LoopController;
use App\Middleware\UserAuthMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

      $app->get('/hi/{name}', \App\Controller\HiController::class);  //loop data from Tasks.php class and shows and image as well

    $app->get('/', HomeAction::class)->setName('home');

    $app->get('/helloworld', HelloAction::class)->setName('hello'); //Lesson one

//    $app->get('/hi/{nameholder}', HiAction::class); //Lesson two

    $app->get('/hello/{name}', ParamsAction::class)->setName('params'); //Lesson two

    $app->get('/first', 'App\Controller\FirstController:homepage');

    $app->get('/second', 'App\Controller\SecondController:second');

    $app->get('/loop', 'App\Controller\LoopController:loop');

    $app->any('/home', 'App\Controller\SearchController:records')->add(UserAuthMiddleware::class);  //admin secret to get access to this route or below one.  Logout deletes the cookies

    $app->any('/search', '\App\Controller\SearchController:search')->add(UserAuthMiddleware::class);

    $app->any('/form', '\App\Controller\SearchController:form');

    $app->any('/default', '\App\Controller\ShopController:default');

    $app->get('/details/{id:[0-9]+}', '\App\Controller\ShopController:details');

    $app->any('/defaultauthed', '\App\Controller\ShopAuthedController:default');  //tuupola basic auth protected by middleware

    $app->get('/detailsauthed/{id:[0-9]+}', '\App\Controller\ShopAuthedController:details');  //tuupola basic auth protected by middleware

    $app->get('/notfound', 'App\Controller\NotFoundController:notfound');

    $app->group('/users', function (RouteCollectorProxy $group) {
  //NB this uses sessions and a password to access admin area  /users
        $group->get('/', \App\Action\User\UserAction::class)->setName('users');
        // add more routes ...
    })->add(UserAuthMiddleware::class);
    $app->get('/users', \App\Action\User\UserAction::class)->setName('users');
    $app->get('/login', \App\Action\Auth\LoginAction::class)->setName('login');
    $app->post('/login', \App\Action\Auth\LoginSubmitAction::class);
    $app->get('/logout', \App\Action\Auth\LogoutAction::class)->setName('logout');
};
