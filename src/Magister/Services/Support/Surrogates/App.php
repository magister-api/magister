<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class App
 * @package Magister
 */
class App extends Surrogate
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'app';
    }
}
