<?php

use App\Middleware\ErrorHandlerMiddleware;
use App\Middleware\ExceptionMiddleware;
use App\Middleware\FlashMessagesMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {

    $app->addBodyParsingMiddleware();
    $app->add(TwigMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(ExceptionMiddleware::class);
 //   $app->add(ErrorHandlerMiddleware::class);
    $app->add(ErrorMiddleware::class);
    $app->addErrorMiddleware(true, true, true);

//    $app->addErrorMiddleware(true, true, true)->setErrorHandler(
//        Slim\Exception\HttpNotFoundException::class,
//        function (Psr\Http\Message\ServerRequestInterface $request) use ($container) {
//            $controller = new App\Controller\ExceptionController($container);
//            return $controller->notFound($request);
//        }
//    );
};
