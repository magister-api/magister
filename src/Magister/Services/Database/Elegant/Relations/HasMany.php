<?php

namespace Magister\Services\Database\Elegant\Relations;

/**
 * Class HasMany.
 */
class HasMany extends HasOneOrMany
{
    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->query->get();
    }
}
