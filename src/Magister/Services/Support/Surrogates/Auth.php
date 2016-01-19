<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Auth
 * @package Magister
 */
class Auth extends Surrogate
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'auth';
    }
}
