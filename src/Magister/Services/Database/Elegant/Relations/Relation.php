<?php

namespace Magister\Services\Database\Elegant\Relations;

use Magister\Services\Database\Elegant\Model;
use Magister\Services\Database\Elegant\Builder;

/**
 * Class Relation
 * @package Magister
 */
abstract class Relation
{
    /**
     * The Elegant query builder instance.
     *
     * @var \Magister\Services\Database\Elegant\Builder
     */
    protected $query;

    /**
     * The parent model instance.
     *
     * @var \Magister\Services\Database\Elegant\Model
     */
    protected $parent;

    /**
     * The related model instance.
     *
     * @var \Magister\Services\Database\Elegant\Model
     */
    protected $related;

    /**
     * Indicates if the relation is adding constraints.
     *
     * @var bool
     */
    protected static $constraints = true;

    /**
     * Create a new relation instance.
     *
     * @param \Magister\Services\Database\Elegant\Builder $query
     * @param \Magister\Services\Database\Elegant\Model $parent
     */
    public function __construct(Builder $query, Model $parent)
    {
        $this->query = $query;
        $this->parent = $parent;
        $this->related = $query->getModel();

        $this->addConstraints();
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    abstract public function addConstraints();

    /**
     * Get the underlying query for the relation.
     *
     * @return \Magister\Services\Database\Elegant\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the base query builder driving the Elegant builder.
     *
     * @return \Magister\Services\Database\Query\Builder
     */
    public function getBaseQuery()
    {
        return $this->query->getQuery();
    }

    /**
     * Get the parent model of the relation.
     *
     * @return \Magister\Services\Database\Elegant\Model
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the related model of the relation.
     *
     * @return \Magister\Services\Database\Elegant\Model
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Handle dynamic method calls to the relationship.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $result = call_user_func_array([$this->query, $method], $parameters);

        if ($result === $this->query) {
            return $this;
        }

        return $result;
    }
}
