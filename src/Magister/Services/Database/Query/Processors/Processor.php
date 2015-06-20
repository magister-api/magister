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
    public function processSelect(Builder $builder, $results)
    {
        if ( ! isset($results['Fouttype']))
        {
            if (array_key_exists('Items', $results))
            {
                return reset($results);
            }
            elseif ( ! is_array(current($results)))
            {
                return [$results];
            }
            else
            {
                return $results;
            }
        }

        return [];
    }
}