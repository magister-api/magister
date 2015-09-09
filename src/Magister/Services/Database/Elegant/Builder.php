<?php

namespace Magister\Services\Database\Elegant;

use Magister\Services\Database\Query\Builder as QueryBuilder;

/**
 * Class Builder.
 */
class Builder
{
    /**
     * The base query builder implementation.
     *
     * @var \Magister\Services\Database\Query\Builder
     */
    protected $query;

    /**
     * The model being queried.
     *
     * @var \Magister\Services\Database\Elegant\Model
     */
    protected $model;

    /**
     * Create a new elegant builder instance.
     *
     * @param \Magister\Services\Database\Query\Builder $query
     */
    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     *
     * @return \Magister\Services\Database\Elegant\Model|\Magister\Services\Support\Collection|null
     */
    public function find($id)
    {
        return $this->get()->find($id);
    }

    /**
     * Execute the query and get the first result.
     *
     * @return \Magister\Services\Database\Elegant\Model|static|null
     */
    public function first()
    {
        return $this->get()->first();
    }

    /**
     * Execute the query as a select statement.
     *
     * @return \Magister\Services\Support\Collection|static[]
     */
    public function get()
    {
        $models = $this->getModels();

        return $this->model->newCollection($models);
    }

    /**
     * Pluck a single column from the database.
     *
     * @param string $column
     *
     * @return mixed
     */
    public function pluck($column)
    {
        $result = $this->first();

        if ($result) {
            return $result->$column;
        }
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
        return $this->query->lists($column, $key);
    }

    /**
     * Get the hydrated models.
     *
     * @return array
     */
    public function getModels()
    {
        $results = $this->query->get();

        $connection = $this->model->getConnectionName();

        return $this->model->hydrate($results, $connection)->all();
    }

    /**
     * Get the underlying query builder instance.
     *
     * @return \Magister\Services\Database\Query\Builder|static
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the underlying query builder instance.
     *
     * @param \Magister\Services\Database\Query\Builder $query
     *
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set the model instance being queried.
     *
     * @param \Magister\Services\Database\Elegant\Model $model
     *
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->query->from($model->getUrl());

        return $this;
    }

    /**
     * Get the model instance being queried.
     *
     * @return \Magister\Services\Database\Elegant\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        call_user_func_array([$this->query, $method], $parameters);

        return $this;
    }
}
