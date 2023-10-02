<?php

use App\Core\Application;

// @codingStandardsIgnoreStart
class m0001_users
{
    /**
     * Create the users table.
     */
    public function up()
    {
        $db = Application::$app->db;
        $SQL = 'CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL,
            password VARCHAR(512) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;';
        $db->pdo->exec($SQL);
    }

    /**
     * Drop the users table.
     */
    public function down()
    {
        $db = Application::$app->db;
        $SQL = 'DROP TABLE users;';
        $db->pdo->exec($SQL);
    }
}
