<?php
namespace Magister\Services\Database\Elegant\Relations;

use Magister\Services\Database\Elegant\Builder;
use Magister\Services\Database\Elegant\Model;

/**
 * Class HasOne
 * @package Magister
 */
class HasOne extends Relation
{
    /**
     * The local key of the parent model.
     *
     * @var string
     */
    protected $localKey;

    /**
     * Create a new has one or many relationship instance.
     *
     * @param \Magister\Services\Database\Elegant\Builder $query
     * @param \Magister\Services\Database\Elegant\Model $parent
     * @param string $localKey
     */
    public function __construct(Builder $query, Model $parent, $localKey)
    {
        $this->localKey = $localKey;

        parent::__construct($query, $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        if (static::$constraints)
        {
            $this->query->where($this->localKey, $this->getParentKey());
        }
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->query->first();
    }

    /**
     * Get the key value of the parent's local key.
     *
     * @return mixed
     */
    public function getParentKey()
    {
        return $this->parent->getAttribute($this->localKey);
    }
}