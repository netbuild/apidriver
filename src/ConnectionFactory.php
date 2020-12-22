<?php

namespace Netbuild\Apidriver;

use Illuminate\Database\Connectors\ConnectionFactory as BaseConnectionFactory;
use Netbuild\Apidriver\Connection\ApiConnection;
use Netbuild\Apidriver\Connector\ApiConnector;

class ConnectionFactory extends BaseConnectionFactory
{
    /**
     * Create a connector instance based on the configuration.
     *
     * @param  array  $config
     * @return \Illuminate\Database\Connectors\ConnectorInterface
     *
     * @throws \InvalidArgumentException
     */
    public function createConnector(array $config)
    {
        if (! isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        if ($this->container->bound($key = "db.connector.{$config['driver']}")) {
            return $this->container->make($key);
        }
        
        switch ($config['driver']) {
            case 'mysql':
                return new \Illuminate\Database\Connectors\MySqlConnector;
            case 'pgsql':
                return new \Illuminate\Database\PostgresConnector;
            case 'sqlite':
                return new \Illuminate\Database\SQLiteConnector;
            case 'sqlsrv':
                return new \Illuminate\Database\SqlServerConnector;
            case 'api':
                return new ApiConnector;
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}]");
    }

    /**
     * Create a new connection instance.
     *
     * @param  string   $driver
     * @param  \PDO|\Closure     $connection
     * @param  string   $database
     * @param  string   $prefix
     * @param  array    $config
     * @return \Illuminate\Database\Connection
     *
     * @throws \InvalidArgumentException
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);
        }

        switch ($driver) {
            case 'mysql':
                return new \Illuminate\Database\MySqlConnection($connection, $database, $prefix, $config);
            case 'pgsql':
                return new \Illuminate\Database\PostgresConnection($connection, $database, $prefix, $config);
            case 'sqlite':
                return new \Illuminate\Database\SQLiteConnection($connection, $database, $prefix, $config);
            case 'sqlsrv':
                return new \Illuminate\Database\SqlServerConnection($connection, $database, $prefix, $config);
            case 'api':
                return new ApiConnection($connection, $database, $prefix, $config);
        }

        throw new InvalidArgumentException("Unsupported driver [$driver]");
    }
}