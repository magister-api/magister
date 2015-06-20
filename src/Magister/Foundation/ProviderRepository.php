<?php
namespace Magister\Foundation;

use Magister\Magister;

/**
 * Class ProviderRepository
 * @package Magister
 */
class ProviderRepository
{
    /**
     * The application implementation.
     *
     * @var \Magister\Magister
     */
    protected $app;

    /**
     * Create a new ProviderRepository instance.
     *
     * @param \Magister\Magister $app
     */
    public function __construct(Magister $app)
    {
        $this->app = $app;
    }

    /**
     * Register the application service providers.
     *
     * @param array $providers
     * @return void
     */
    public function load(array $providers)
    {
        foreach ($providers as $provider)
        {
            $this->app->register($this->createProvider($provider));
        }
    }

    /**
     * Create a new provider instance.
     *
     * @param string $provider
     * @return \Magister\Services\Support\ServiceProvider
     */
    public function createProvider($provider)
    {
        return new $provider($this->app);
    }
}