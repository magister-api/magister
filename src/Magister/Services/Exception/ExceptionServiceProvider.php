<?php

namespace Magister\Services\Exception;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Magister\Services\Support\ServiceProvider;

/**
 * Class ExceptionServiceProvider.
 */
class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        if (class_exists('Whoops\Run')) {
            $this->registerPrettyWhoopsHandler();
            $this->registerWhoops();
        }
    }

    /**
     * Register the Whoops error display service.
     *
     * @return void
     */
    protected function registerWhoops()
    {
        // We will instruct Whoops to not exit after it displays the exception as it
        // will otherwise run out before we can do anything else. We just want to
        // let the framework go ahead and finish a request on this end instead.
        with($whoops = new Run)->allowQuit(false);

        $whoops->pushHandler($this->app['whoops.handler']);

        $whoops->register();
    }

    /**
     * Register the "pretty" Whoops handler.
     *
     * @return void
     */
    protected function registerPrettyWhoopsHandler()
    {
        $this->app['whoops.handler'] = $this->app->share(function () {
            with($handler = new PrettyPageHandler)->setEditor('sublime');

            return $handler;
        });
    }
}
