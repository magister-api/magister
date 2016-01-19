<?php

namespace Magister\Services\Database;

/**
 * Interface ConnectionInterface
 * @package Magister
 */
interface ConnectionInterface
{
    /**
     * Start a query against the server.
     *
     * @param string $query
     * @return \Magister\Services\Database\Query\Builder
     */
    public function query($query);

    /**
     * Run a select statement against the server.
     *
     * @param string $query
     * @param array $bindings
     * @return mixed
     */
    public function select($query, $bindings = []);
}
