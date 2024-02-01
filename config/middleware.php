<?php

use App\Error\Renderer\HtmlErrorRenderer;
use App\Middleware\HttpExceptionMiddleware;
use App\Middleware\ErrorHandlerMiddleware;
use App\Middleware\ExceptionMiddleware;
use App\Middleware\FlashMessagesMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\TwigMiddleware;
use Tuupola\Middleware\HttpBasicAuthentication;

return function (App $app) {

    $app->addBodyParsingMiddleware();
    $app->add(TwigMiddleware::class);
    $app->add(SessionStartMiddleware::class);
    $app->addRoutingMiddleware();
//    $app->add(HttpBasicAuthentication::class);  //locks down all links on the site
    $app->add(new Tuupola\Middleware\HttpBasicAuthentication([
        "path" => ["/defaultauthed", "/detailsauthed"], /* or ["/admin", "/api"] */
        "realm" => "Protected",
        "users" => [
            "admin" => "secret",
            "user" => "password"
        ]
    ]));
//    $app->add(new Zeuxisoo\Whoops\Slim\WhoopsMiddleware());
    $app->add(BasePathMiddleware::class);
//    $app->add(ExceptionMiddleware::class);
//    $app->add(HttpExceptionMiddleware::class);
    $app->add(ErrorHandlerMiddleware::class);
    $app->add(ErrorMiddleware::class);
};
