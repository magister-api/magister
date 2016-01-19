<?php

namespace Magister\Services\Database;

/**
 * Class DatabaseManager
 * @package Magister
 */
class DatabaseManager implements ConnectionResolverInterface
{
    /**
     * The application instance.
     *
     * @var \Magister\Magister
     */
    protected $app;

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = [];

    /**
     * Create a new database manager instance.
     *
     * @param \Magister\Magister $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get a database connection instance.
     *
     * @param string $name
     * @return \Magister\Services\Database\Connection
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (! isset($this->connections[$name])) {
            $connection = $this->makeConnection();

            $this->connections[$name] = $connection;
        }

        return $this->connections[$name];
    }

    /**
     * Make the database connection instance.
     *
     * @return \Magister\Services\Database\Connection
     */
    protected function makeConnection()
    {
        return new Connection($this->app['http']);
    }

    /**
     * Add a connection to the resolver.
     *
     * @param string $name
     * @param \Magister\Services\Database\ConnectionInterface $connection
     * @return void
     */
    public function addConnection($name, ConnectionInterface $connection)
    {
        $this->connections[$name] = $connection;
    }

    /**
     * Set the default connection name.
     *
     * @param string $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->app['config']['database.default'] = $name;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['database.default'];
    }

    /**
     * Return all of the created connections.
     *
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->connection(), $method], $parameters);
    }
}
