<?php

namespace Tests;

use Dotenv\Dotenv;
use App\Core\Application;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    /**
     * Set up the test case.
     */
    public function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $config = [
            'userClass' => 'App\Models\User',
            'db' => [
                'dsn' => $_ENV['DB_DSN'].'_test',
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
            ],
        ];

        $app = new Application(__DIR__.'/../app', $config);

        $app->db->rollbackMigrations();
        $app->db->applyMigrations();
        $app->db->runSeeders();
    }
}
