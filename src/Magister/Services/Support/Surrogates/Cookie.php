<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Cookie
 * @package Magister
 */
class Cookie extends Surrogate
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'cookie';
    }
}
