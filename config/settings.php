<?php


// Detect environment
$_ENV['APP_ENV'] ??= $_SERVER['APP_ENV'] ?? 'dev';

// Load default settings
$settings = require __DIR__ . '/defaults.php';

// Overwrite default settings with environment specific local settings
$configFiles = [
    __DIR__ . sprintf('/local.%s.php', $_ENV['APP_ENV']),
    __DIR__ . '/env.php',
    __DIR__ . '/../../env.php',
];

// Database settings
$settings['db'] = [
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'driverOptions' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
    ],
];

// Session
$settings['session'] = [
    'name' => 'webapp',
];

$settings['image_manager'] = [
    // configure image driver (gd by default)
    'driver' => 'gd',
];

// Logger settings
$settings['logger'] = [
    // Log file location
    'path' => __DIR__ . '/../logs',
    // Default log level
    'level' => \Psr\Log\LogLevel::DEBUG,
];

$settings['view'] = [
    // Path to templates
    'path' => __DIR__ . '/../templates',
    // Default attributes
    'attributes' => [],
];

//$settings['api_auth'] = [
//    'users' => [
//        'admin' => 'secret',
//        'user' => 'password',
//    ],
//];
/// Below is above encrypted and should be pulled from .env.  Not got there yet
$settings['api_auth'] = [
    'users' => [
        'admin' => '$2y$10$JKLleIlqaHs1HVbRAE2G3.sOmLMbxWHBu6dnd8iQ3BFvhw9wE1S9m',
        'user' => '$2y$10$BAcmFwDluG.7eKcVbpcl.uEdBa6MWQkJp2NPufgn7ScU6aR2CrOBC',
    ],
];

// Error Handling Middleware settings
$settings['error'] = [

    // Should be set to false in production
    'display_error_details' => true,

    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the "displayErrorDetails" setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,

    // Display error details in error log
    'log_error_details' => true,
];


// Twig settings
$settings['twig'] = [
    // Template paths
    'paths' => [
        __DIR__ . '/../templates',
    ],
    // Twig environment options
    'options' => [
        // Should be set to true in production
        'cache_enabled' => false,
        'cache_path' => __DIR__ . '/../tmp/twig',
    ],
];

foreach ($configFiles as $configFile) {
    if (!file_exists($configFile)) {
        continue;
    }

    $local = require $configFile;
    if (is_callable($local)) {
        $settings = $local($settings);
    }
}

return $settings;
