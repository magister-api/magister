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
        $this->app->singleton('auth', function($app)
        {
            $auth = new AuthManager($app);

            $auth->setDefaultDriver('Elegant');

            return $auth;
        });
    }

    /**
     * Perform booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->auth->attempt($this->app['credentials']);
    }
}