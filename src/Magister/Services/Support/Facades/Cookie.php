<?php
namespace Magister\Services\Support\Facades;

use Magister\Services\Support\Facade;

/**
 * Class Cookie
 * @package Magister
 */
class Cookie extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cookie';
    }
}