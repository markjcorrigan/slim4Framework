<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

// Looing for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');
$dotenv->load();

//// Retrive env variable
//$userName = $_ENV['DB_NAME'];
//
//var_dump($userName);

// Build DI container instance
$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/container.php')
    ->build();

// Create App instance
return $container->get(App::class);
