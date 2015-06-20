<?php
namespace Magister\Services\Exception;

use Magister\Services\Support\ServiceProvider;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

/**
 * Class ExceptionServiceProvider
 * @package Magister
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
        $this->registerPrettyWhoopsHandler();
        $this->registerWhoops();
    }

    /**
     * Register the Whoops error display service.
     *
     * @return \Whoops\Run
     */
    protected function registerWhoops()
    {
        with($whoops = new Run)->allowQuit(false);

        $whoops->pushHandler($this->app['whoops.handler']);

        return $whoops->register();
    }

    /**
     * Register the "pretty" Whoops handler.
     *
     * @return void
     */
    protected function registerPrettyWhoopsHandler()
    {
        $this->app->singleton('whoops.handler', function($app)
        {
            with($handler = new PrettyPageHandler)->setEditor('sublime');

            return $handler;
        });
    }
}