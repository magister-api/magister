<?php

namespace Magister\Services\Database\Elegant\Relations;

/**
 * Class HasOne
 * @package Magister
 */
class HasOne extends HasOneOrMany
{
    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->query->first();
    }
}
