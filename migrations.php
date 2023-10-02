<?php

use App\Core\Application;
use Dotenv\Dotenv;

require_once realpath(__DIR__.'/vendor/autoload.php');

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];

$app = new Application(__DIR__.'/app', $config);

// Rollback migration in case of passing 'rollback' argument.
isset($argv[1]) && 'rollback' === $argv[1]
    ? $app->db->rollbackMigrations()
    : $app->db->applyMigrations();

// Run Seeders in case of passing 'seed' argument.
if (isset($argv[1]) && 'seed' === $argv[1]) {
    $app->db->runSeeders();
}
