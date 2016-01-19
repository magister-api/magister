<?php

namespace Magister\Services\Database;

/**
 * Class QueryException
 * @package Magister
 */
class QueryException extends \Exception
{
    /**
     * The url which the query is targeting.
     *
     * @var string
     */
    protected $query;

    /**
     * The bindings for the query.
     *
     * @var array
     */
    protected $bindings;

    /**
     * Create a new query exception instance.
     *
     * @param string $query
     * @param array $bindings
     * @param \Exception $previous
     */
    public function __construct($query, array $bindings, $previous)
    {
        parent::__construct('', 0, $previous);

        $this->query = $query;
        $this->bindings = $bindings;
        $this->previous = $previous;
        $this->code = $previous->getCode();
        $this->message = $this->formatMessage($query, $bindings, $previous);
    }

    /**
     * Format the error message.
     *
     * @param string $query
     * @param array $bindings
     * @param \Exception $previous
     * @return string
     */
    protected function formatMessage($query, $bindings, $previous)
    {
        return $previous->getMessage() . ' (Query: ' . $query . ', ' . print_r($bindings, true) . ')';
    }

    /**
     * Get the url which the query is targeting.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the bindings for the query.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }
}
