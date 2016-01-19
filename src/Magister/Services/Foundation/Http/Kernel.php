<?php

namespace Magister\Services\Foundation\Http;

use Magister\Magister;

/**
 * Class Kernel
 * @package Magister
 */
class Kernel
{
    /**
     * The application implementation.
     *
     * @var \Magister\Magister
     */
    protected $app;

    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Magister\Services\Foundation\Bootstrap\LoadConfiguration',
        'Magister\Services\Foundation\Bootstrap\RegisterSurrogates',
        'Magister\Services\Foundation\Bootstrap\RegisterProviders',
        'Magister\Services\Foundation\Bootstrap\BootProviders',
        'Magister\Services\Foundation\Bootstrap\MakeReplacements'
    ];

    /**
     * Create a new kernel instance.
     *
     * @param \Magister\Magister $app
     */
    public function __construct(Magister $app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap the application for http requests.
     *
     * @return void
     */
    public function bootstrap()
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }

    /**
     * Get the Magister application instance.
     *
     * @return \Magister\Magister
     */
    public function getApplication()
    {
        return $this->app;
    }
}
