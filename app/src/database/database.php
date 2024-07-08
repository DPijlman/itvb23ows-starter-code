<?php
namespace HiveGame\Database;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct($connection = null)
    {
        if ($connection === null) {
            $this->connection = new \mysqli('ows-db1', 'owsuser', 'Ows1234user', 'hive');
            if ($this->connection->connect_error) {
                die('Connection failed: ' . $this->connection->connect_error);
            }
        } else {
            $this->connection = $connection;
        }
    }

    public static function getInstance($connection = null)
    {
        if (self::$instance === null) {
            self::$instance = new Database($connection);
        }
        return self::$instance;
    }

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
