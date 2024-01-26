<?php

use App\Error\Renderer\HtmlErrorRenderer;
use App\Support\Logger\LoggerFactory;
use App\Support\Logger\LoggerFactoryInterface;
use Doctrine\DBAL\Configuration as DoctrineConfiguration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\PhpRenderer;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Controller\NotFoundController;

return [

    // Application settings
    'settings' => fn () => require __DIR__ . '/settings.php',

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Register routes
        (require __DIR__ . '/routes.php')($app);

        // Add MethodOverride middleware  /https://discourse.slimframework.com/t/405-method-not-allowed/4282/11
         $methodOverrideMiddleware = new MethodOverrideMiddleware();
         $app->add($methodOverrideMiddleware);

        // Register middleware
        (require __DIR__ . '/middleware.php')($app);



        return $app;
    },

    // Database connection
    Connection::class => function (ContainerInterface $container) {
        $config = new DoctrineConfiguration();
        $connectionParams = $container->get('settings')['db'];

        return DriverManager::getConnection($connectionParams, $config);
    },

    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getNativeConnection();
    },

    PhpRenderer::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['view'];

        return new PhpRenderer($settings['path'], $settings['attributes']);
    },

    // HTTP factories
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    LoggerFactoryInterface::class => function (ContainerInterface $container) {
        return new LoggerFactory($container->get('settings')['logger']);
    },



    LoggerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];
        $logger = new Logger('app');

        $filename = sprintf('%s/app.log', $settings['path']);
        $level = $settings['level'];
        $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level, true, 0777);
        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));
        $logger->pushHandler($rotatingFileHandler);

        return $logger;
    },

    // Twig templates
    Twig::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $twigSettings = $settings['twig'];

        $options = $twigSettings['options'];
        $options['cache'] = $options['cache_enabled'] ? $options['cache_path'] : false;

        $twig = Twig::create($twigSettings['paths'], $options);

        // Add extension here
        // ...
        $twig->getEnvironment()->addGlobal('flash', $container->get(Messages::class));

        return $twig;
    },

    TwigMiddleware::class => function (ContainerInterface $container) {
        return TwigMiddleware::createFromContainer(
            $container->get(App::class),
            Twig::class
        );
    },

    Messages::class => function () {
        $storage = [];
        return new Messages($storage);
    },



//    ErrorMiddleware::class => function (ContainerInterface $container) {
//        $app = $container->get(App::class);
//        $settings = $container->get('settings')['error'];
//
//        $logger = $container->get(LoggerFactoryInterface::class)
//            ->addFile('error.log')
//            ->createLogger();
//
//        $errorMiddleware = new ErrorMiddleware(
//            $app->getCallableResolver(),
//            $app->getResponseFactory(),
//            (bool)$settings['display_error_details'],
//            (bool)$settings['log_errors'],
//            (bool)$settings['log_error_details'],
//            $logger
//        );

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        $logger = $container->get(LoggerFactoryInterface::class)
            ->addFile('error.log')
            ->createLogger();

        $errorMiddleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details'],
            $logger
        );

        $errorMiddleware->setErrorHandler(
            HttpException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write('500 Internal Server Error');
                return $response->withStatus(500);
            }
        );
        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
            //                $response->getBody()->write('404 Not Found From the message in container.php');
            //                return $response->withStatus(404);
                return $response
                    ->withHeader('Location', '/notfound')
                    ->withStatus(404);
            }
        );
        return $errorMiddleware;
    },

];
