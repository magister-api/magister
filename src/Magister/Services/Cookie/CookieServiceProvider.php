<?php
namespace Magister\Services\Cookie;

use Magister\Services\Support\ServiceProvider;

/**
 * Class CookieServiceProvider
 * @package Magister
 */
class CookieServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('cookie', new Cookie());
    }
}