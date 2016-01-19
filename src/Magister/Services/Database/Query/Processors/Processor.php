<?php

namespace Magister\Services\Database\Query\Processors;

use Magister\Services\Database\Query\Builder;

/**
 * Class Processor
 * @package Magister
 */
class Processor
{
    /**
     * Process the selected results.
     *
     * @param \Magister\Services\Database\Query\Builder $builder
     * @param array $results
     * @return array
     */
    public function process(Builder $builder, $results)
    {
        return process($results);
    }
}
