<?php
namespace Magister\Services\Support\Facades;

use Magister\Services\Support\Facade;

/**
 * Class Http
 * @package Magister
 */
class Http extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'http';
    }
}