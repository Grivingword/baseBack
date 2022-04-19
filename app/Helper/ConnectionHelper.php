<?php

namespace App\Helper;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

/***
 *  ConnectionHelper::createDataBaseConnection($instance->instanceName);
 */
class ConnectionHelper
{
    /**
     *
     * Creates a connection
     * @param string|null $connectionName
     */
    public static function createDataBaseConnection(string $connectionName = null) {

        logger("creating database connection database.connections.$connectionName");

        Config::set("database.connections.mysql", [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $connectionName,
            'username' => env('DB_USERNAME', '11'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);


            DB::purge('mysql');

        self::setDBConnection('mysql');
    }

    /**
     * set a database connection
     * @param string $connectionName
     * @return ConnectionInterface
     */
    public static function setDBConnection(string $connectionName): ConnectionInterface
    {
//        logger(__METHOD__." $connectionName");

        return DB::connection($connectionName);
    }
}
