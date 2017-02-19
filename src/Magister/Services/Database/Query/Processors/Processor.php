<?php

namespace Magister\Services\Database\Query\Processors;

use Magister\Services\Database\Query\Builder;

/**
 * Class Processor.
 */
class Processor
{
    /**
     * Process the selected results.
     *
     * @param \Magister\Services\Database\Query\Builder $builder
     * @param array                                     $results
     *
     * @return array
     */
    public function process(Builder $builder, $results)
    {
        if (!isset($results) || isset($results['Fouttype'])) {
            return [];
        }

        if (array_has($results, 'Items') || array_has($results, 'items')) {
            return reset($results);
        }

        foreach ($results as $result) {
            if (!is_array($result)) {
                return [$results];
            }
        }

        return $results;
    }
}
