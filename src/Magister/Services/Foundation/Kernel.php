<?php

namespace Magister\Services\Foundation;

use Magister\Magister;

/**
 * Class Kernel.
 */
class Kernel
{
    /**
     * The API implementation.
     *
     * @var \Magister\Magister
     */
    protected $api;
    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Magister\Services\Foundation\LoadConfiguration',
    ];

    /**
     * Create a new kernel instance.
     *
     * @param \LinkedIn\LinkedIn $api
     */
    public function __construct(Magister $api)
    {
        $this->api = $api;
    }

    /**
     * Bootstrap the application for http requests.
     *
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->api->hasBeenBootstrapped()) {
            $this->api->bootstrapWith($this->bootstrappers());
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
     * Get the API instance.
     *
     * @return \Magister\Magister
     */
    public function getApi()
    {
        return $this->api;
    }
}
