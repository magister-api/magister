<?php

namespace Magister\Services\Database;

use Magister\Services\Database\Elegant\Model;
use Magister\Services\Support\ServiceProvider;

/**
 * Class DatabaseServiceProvider.
 */
class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app);
        });
    }

    /**
     * Perform booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
    }
}
