<?php
namespace Magister\Services\Support\Facades;

use Magister\Services\Support\Facade;

/**
 * Class Auth
 * @package Magister
 */
class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth';
    }
}