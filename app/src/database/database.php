<?php
namespace HiveGame\Database;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connection = new \mysqli('ows-db1', 'owsuser', 'Ows1234user', 'hive');
        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
