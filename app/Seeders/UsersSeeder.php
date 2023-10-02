<?php

use App\Core\Application;

// @codingStandardsIgnoreStart
class UsersSeeder
{
    /**
     * Seed the users table.
     */
    public function run()
    {
        $db = Application::$app->db;
        $name = 'admin';

        // Check if User with the given name already exists.
        $select = "SELECT * FROM users WHERE name = '$name';";
        $statement = $db->pdo->prepare($select);
        $statement->execute();
        $user = $statement->fetch();

        if (!$user) {
            $password = password_hash('password', PASSWORD_DEFAULT);
            $SQL = "INSERT INTO users (name, email, status, password) 
                VALUES ('admin', 'admin@admin.com', 1, '$password');";
            $db->pdo->exec($SQL);
        }
    }
}
