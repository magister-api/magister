<?php

namespace Magister\Services\Contracts\Foundation;

use Magister\Services\Support\ServiceProvider;

/**
 * Interface Application
 * @package Magister
 */
interface Application
{
    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version();

    /**
     * Get the base path of the Magister installation.
     *
     * @return string
     */
    public function basePath();

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot();

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerProviders();

    /**
     * Register a service provider with the application.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     * @param array $options
     * @return $this
     */
    public function register(ServiceProvider $provider, $options = []);
}
