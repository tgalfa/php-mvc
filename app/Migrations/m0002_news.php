<?php

use App\Core\Application;

// @codingStandardsIgnoreStart
class m0002_news
{
    /**
     * Create the news table.
     */
    public function up()
    {
        $db = Application::$app->db;
        $SQL = 'CREATE TABLE news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;';
        $db->pdo->exec($SQL);
    }

    /**
     * Drop the news table.
     */
    public function down()
    {
        $db = Application::$app->db;
        $SQL = 'DROP TABLE news;';
        $db->pdo->exec($SQL);
    }
}
