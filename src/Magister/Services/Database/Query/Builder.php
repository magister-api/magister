<?php

namespace Magister\Services\Database\Query;

use BadMethodCallException;
use Magister\Services\Database\ConnectionInterface;
use Magister\Services\Database\Query\Processors\Processor;
use Magister\Services\Support\Collection;

/**
 * Class Builder.
 */
class Builder
{
    /**
     * The connection instance.
     *
     * @var \Magister\Services\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * The processor instance.
     *
     * @var \Magister\Services\Database\Query\Processors\Processor
     */
    protected $processor;

    /**
     * The url which the query is targeting.
     *
     * @var string
     */
    protected $from;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Create a new query builder instance.
     *
     * @param \Magister\Services\Database\ConnectionInterface        $connection
     * @param \Magister\Services\Database\Query\Processors\Processor $processor
     */
    public function __construct(ConnectionInterface $connection, Processor $processor)
    {
        $this->connection = $connection;
        $this->processor = $processor;
    }

    /**
     * Set the url which the query is targeting.
     *
     * @param string $query
     *
     * @return $this
     */
    public function from($query)
    {
        $this->from = $query;

        return $this;
    }

    /**
     * Get an array with the values of a given column.
     *
     * @param string $column
     * @param string $key
     *
     * @return array
     */
    public function lists($column, $key = null)
    {
        $columns = $this->getListSelect($column, $key);

        $results = new Collection($this->get());

        return $results->lists($columns[0], array_get($columns, 1));
    }

    /**
     * Get the columns that should be used in a lists array.
     *
     * @param string $column
     * @param string $key
     *
     * @return array
     */
    protected function getListSelect($column, $key)
    {
        $select = is_null($key) ? [$column] : [$column, $key];

        return array_map(function ($column) {
            $dot = strpos($column, '.');

            return $dot === false ? $column : substr($column, $dot + 1);
        }, $select);
    }

    /**
     * Execute the query as a select statement.
     *
     * @return array
     */
    public function get()
    {
        return $this->processor->process($this, $this->runSelect());
    }

    /**
     * Run the query as a select statement against the connection.
     *
     * @return array
     */
    protected function runSelect()
    {
        return $this->connection->select($this->from, $this->getBindings());
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return $this
     */
    public function where($column, $value)
    {
        $this->bindings[$column] = $value;

        return $this;
    }

    /**
     * Handles dynamic "where" clauses to the query.
     *
     * @param string $method
     * @param string $parameters
     *
     * @return $this
     */
    public function dynamicWhere($method, $parameters)
    {
        $finder = substr($method, 5);

        $segments = preg_split('/(And)(?=[A-Z])/', $finder, -1, PREG_SPLIT_DELIM_CAPTURE);

        $parameter = array_shift($parameters);

        foreach ($segments as $segment) {
            if ($segment != 'And') {
                $this->where($segment, $parameter);
            }
        }

        return $this;
    }

    /**
     * Get the raw array of bindings.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Get the connection instance.
     *
     * @return \Magister\Services\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get the query processor instance.
     *
     * @return \Magister\Services\Database\Query\Processors\Processor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * Handle dynamic method calls into the method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'where')) {
            return $this->dynamicWhere($method, $parameters);
        }

        $className = get_class($this);

        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }
}
