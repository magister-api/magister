<?php

namespace Magister\Services\Database;

/**
 * Interface ConnectionResolverInterface
 * @package Magister
 */
interface ConnectionResolverInterface
{
    /**
     * Get a database connection instance.
     *
     * @param string $name
     * @return \Magister\Services\Database\Connection
     */
    public function connection($name = null);

    /**
     * Set the default connection name.
     *
     * @param string $name
     * @return void
     */
    public function setDefaultConnection($name);

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection();
}
