<?php
namespace HiveGame\Database;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct($connection = null)
    {
        if ($connection === null) {
            $host = getenv('HOST') ?: 'ows-db1';
            $user = getenv('USER') ?: 'owsuser';
            $password = getenv('PASSWORD') ?: 'Ows1234user';
            $dbName = getenv('NAME') ?: 'hive';
            $this->connection = new \mysqli($host, $user, $password, $dbName);
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
