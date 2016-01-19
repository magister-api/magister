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
        $this->app->singleton('cookie', function ($app) {
            $cookie = new CookieJar($app['encrypter']);

            $config = $app['config']['session'];

            return $cookie->setDefaultPathAndDomain($config['path'], $config['domain']);
        });
    }
}
