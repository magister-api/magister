<?php

namespace Magister\Services\Auth;

use Magister\Services\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package Magister
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthenticator();
    }

    /**
     * Register the authenticator services.
     *
     * @return void
     */
    protected function registerAuthenticator()
    {
        $this->app->singleton('auth', function ($app) {
            return new AuthManager($app);
        });
    }

    /**
     * Perform booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->bound('credentials')) {
            $auth = $this->app->auth;

            if (! $auth->check()) {
                $auth->attempt($this->app['credentials']);
            }
        }
    }
}
