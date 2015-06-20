<?php
namespace Magister\Services\Support\Facades;

use Magister\Services\Support\Facade;

/**
 * Class DB
 * @package Magister
 */
class DB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}