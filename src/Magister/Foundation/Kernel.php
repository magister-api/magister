<?php
namespace Magister\Foundation;

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
        'Magister\Foundation\Bootstrap\LoadConfiguration',
        'Magister\Foundation\Bootstrap\RegisterFacades',
        'Magister\Foundation\Bootstrap\RegisterProviders',
        'Magister\Foundation\Bootstrap\BootProviders',
        'Magister\Foundation\Bootstrap\MakeReplacements'
    ];

    /**
     * Create a new Kernel instance.
     *
     * @param \Magister\Magister $app
     */
    public function __construct(Magister $app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap the application for the HTTP Requests.
     *
     * @return void
     */
    public function bootstrap()
    {
        if ( ! $this->app->hasBeenBootstrapped())
        {
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
}