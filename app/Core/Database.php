<?php

namespace App\Core;

use PDO;
use PDOStatement;

class Database
{
    /**
     * PDO instance.
     */
    public PDO $pdo;

    /**
     * Create a new Database instance.
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Prepare the SQL statement.
     */
    public function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * Fetch a single record from the database.
     */
    public function fetch(PDOStatement $statement): array|bool
    {
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all records from the database.
     */
    public function fetchAll(PDOStatement $statement): array
    {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Apply the migrations.
     */
    public function applyMigrations(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/Migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        $newMigrations = [];

        foreach ($toApplyMigrations as $migration) {
            if ('.' === $migration || '..' === $migration) {
                continue;
            }

            require_once Application::$ROOT_DIR . '/Migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");

            $newMigrations[] = $migration;
        }

        !empty($newMigrations)
            ? $this->saveMigrations($newMigrations)
            : $this->log('All migrations are applied');
    }

    /**
     * Rollback the migrations.
     */
    public function rollbackMigrations(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/Migrations');
        $toRollbackMigrations = array_intersect($files, $appliedMigrations);

        $rolledbackMigrations = [];

        foreach ($toRollbackMigrations as $migration) {
            if ('.' === $migration || '..' === $migration) {
                continue;
            }

            require_once Application::$ROOT_DIR . '/Migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Rolling back migration $migration");
            $instance->down();
            $this->log("Rolled back migration $migration");

            $rolledbackMigrations[] = $migration;
        }

        !empty($rolledbackMigrations)
            ? $this->removeMigrations($rolledbackMigrations)
            : $this->log('All migrations are rolled back');
    }

    /**
     * Run the seeders.
     */
    public function runSeeders(): void
    {
        $files = scandir(Application::$ROOT_DIR . '/Seeders');

        foreach ($files as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }

            require_once Application::$ROOT_DIR . '/Seeders/' . $file;
            $className = pathinfo($file, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Running seeder $file");
            $instance->run();
            $this->log("Ran seeder $file");
        }
    }

    /**
     * Create the migrations table.
     */
    protected function createMigrationsTable(): void
    {
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ');
    }

    /**
     * Get the applied migrations list.
     */
    protected function getAppliedMigrations(): array
    {
        $statement = $this->prepare('SELECT migration FROM migrations');
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Log the message.
     */
    protected function log(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }

    /**
     * Save the applied migrations list.
     */
    protected function saveMigrations(array $migrations): void
    {
        $str = implode(',', array_map(fn ($m) => "('$m')", $migrations));

        $statement = $this->prepare("
            INSERT INTO migrations (migration) VALUES
                $str
        ");

        $statement->execute();
    }

    /**
     * Remove the applied migrations list.
     */
    protected function removeMigrations(array $migrations): void
    {
        $str = implode(',', array_map(fn ($m) => "'$m'", $migrations));

        $statement = $this->prepare("
            DELETE FROM migrations WHERE migration IN ($str)
        ");

        $statement->execute();
    }
}
