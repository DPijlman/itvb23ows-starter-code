<?php
namespace App\database;

use mysqli;

class Database
{
    private static $conn;

    public function __construct()
    {
        $servername = "ows-db1";
        $username = getenv('MYSQL_USER') ?: 'owsuser';
        $password = getenv('MYSQL_PASSWORD') ?: 'Ows1234user';
        $database = getenv('MYSQL_DATABASE') ?: 'hive';

        if (self::$conn === null) {
            self::$conn = new mysqli($servername, $username, $password, $database);

            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }

            if (!mysqli_set_charset(self::$conn, "utf8mb4")) {
                die("Error setting charset: " . mysqli_error(self::$conn));
            }
        }
    }

    public static function getConnection()
    {
        if (self::$conn === null) {
            new self();
        }
        return self::$conn;
    }
}
