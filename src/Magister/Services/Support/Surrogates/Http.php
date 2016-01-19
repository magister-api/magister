<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Http
 * @package Magister
 */
class Http extends Surrogate
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'http';
    }
}
