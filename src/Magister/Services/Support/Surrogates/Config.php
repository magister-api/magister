<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Config
 * @package Magister
 */
class Config extends Surrogate
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'config';
    }
}
