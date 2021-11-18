<?php
namespace App\Datasource;

use PDO;

class PDOSource
{
    private static $connection = null;

    /**
     * Constructor
     * 
     * @todo: add ability to dynamically select DB based on environment
     */
    private function __construct()
    {
        self::$connection = new PDO(
            'sqlite:data/dev.sqlite3', null, null, [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public static function getConnection()
    {
        if (self::$connection == null) { new PDOSource(); }
        return self::$connection;
    }
}