<?php
namespace Magister\Services\Support\Facades;

use Magister\Services\Support\Facade;

/**
 * Class App
 * @package Magister
 */
class App extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'app';
    }
}