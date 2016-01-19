<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Crypt
 * @package Magister
 */
class Crypt extends Surrogate
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'encrypter';
    }
}
