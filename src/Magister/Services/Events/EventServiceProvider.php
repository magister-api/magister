<?php

namespace Magister\Services\Events;

use Magister\Services\Support\ServiceProvider;

/**
 * Class EventServiceProvider
 * @package Magister
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('events', function ($app) {
            return new Dispatcher($app);
        });
    }
}
