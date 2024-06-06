<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

class DataBase
{
    private $pdo;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . './');
        $dotenv->load();

        $dbHost = $_ENV['DB_HOST'];
        $dbUsername = $_ENV['DB_USERNAME'];
        $dbPassword = $_ENV['DB_PASSWORD'];
        $dbName = $_ENV['DB_DATABASE'];

        try {
            $this->pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
