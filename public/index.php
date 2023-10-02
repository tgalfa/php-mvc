<?php

use App\Controllers\AuthController;
use App\Controllers\NewsController;
use App\Core\Application;
use Dotenv\Dotenv;

require_once realpath(__DIR__ . '/../vendor/autoload.php');

// Load environment variables.
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Set the application configuration.
$config = [
    'userClass' => 'App\Models\User',
    'baseUrl' => $_ENV['BASE_URL'],
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];

// Create a new application.
$app = new Application(dirname(__DIR__) . '/app', $config);

// Define the application routes.
$app->router->get('/', [AuthController::class, 'login']);
$app->router->post('/', [AuthController::class, 'login']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/news', [NewsController::class, 'index']);
$app->router->post('/news', [NewsController::class, 'store']);
$app->router->post('/news/{id}/update', [NewsController::class, 'update']);
$app->router->post('/news/{id}/delete', [NewsController::class, 'delete']);

// Run the application.
$app->run();
