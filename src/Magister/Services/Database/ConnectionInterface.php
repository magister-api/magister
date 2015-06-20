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
     * @param string $url
     * @return \Magister\Services\Database\Query\Builder
     */
    public function url($url);

    /**
     * Run a select statement against the server.
     *
     * @param string $url
     * @param array $bindings
     * @return mixed
     */
    public function select($url, $bindings = []);
}