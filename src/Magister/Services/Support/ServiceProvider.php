<?php
namespace Magister\Services\Support;

use Magister\Magister;
use Magister\Exceptions\BadMethodCallException;

/**
 * Class ServiceProvider
 * @package Magister
 */
abstract class ServiceProvider
{
    /**
     * The application implementation.
     *
     * @var \Magister\Magister
     */
    protected $app;

    /**
     * Create a new ServiceProvider instance.
     *
     * @param \Magister\Magister $app
     */
    public function __construct(Magister $app)
    {
        $this->app = $app;
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    abstract public function register();

    /**
     * Dynamically handle missing method calls.
     *
     * @param string $method
     * @param array $parameters
     * @throws \Magister\Exceptions\BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if ($method == 'boot') return;

        throw new BadMethodCallException(sprintf('Call to undefined method "%s"', $method));
    }
}