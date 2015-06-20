<?php
namespace Magister\Services\Support;

/**
 * Class Manager
 * @package Magister
 */
abstract class Manager
{
    /**
     * The application instance.
     *
     * @var \Magister\Magister
     */
    protected $app;

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Create a new AuthManager instance.
     *
     * @param \Magister\Magister $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    abstract public function getDefaultDriver();

    /**
     * Get the auth driver instance.
     *
     * @param string $driver
     * @return \Magister\Services\Auth\Auth
     */
    public function driver($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if ( ! isset($this->drivers[$driver]))
        {
            $this->drivers[$driver] = $this->createDriver($driver);
        }

        return $this->drivers[$driver];
    }

    /**
     * Create a new driver instance.
     *
     * @param string $driver
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        $method = 'create' . ucfirst($driver) . 'Driver';

        if (method_exists($this, $method))
        {
            return $this->{$method}();
        }

        throw new \InvalidArgumentException(sprintf('Driver "%s" is not supported.', $driver));
    }

    /**
     * Get all of the created "drivers".
     *
     * @return array
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * Dynamically call the auth instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->driver(), $method], $parameters);
    }
}