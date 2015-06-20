<?php
namespace Magister\Services\Auth;

use Magister\Services\Support\Manager;

/**
 * Class AuthManager
 * @package Magister\Services\Auth
 */
class AuthManager extends Manager
{
    /**
     * The default driver name.
     *
     * @var string
     */
    protected $default;

    /**
     * Create a new driver instance.
     *
     * @param string $driver
     * @return mixed
     */
    protected function createDriver($driver)
    {
        return parent::createDriver($driver);
    }

    /**
     * Create an instance of the Elegant driver.
     *
     * @return \Magister\Services\Auth\Auth
     */
    public function createElegantDriver()
    {
        $provider = $this->createElegantProvider();

        return new Guard($provider);
    }

    /**
     * Create an instance of the Elegant user provider.
     *
     * @return \Magister\Services\Database\Elegant\Model
     */
    protected function createElegantProvider()
    {
        $model = $this->app['config']['auth.model'];

        return new ElegantUserProvider($this->app['http'], $model);
    }

    /**
     * Set the default authentication driver name.
     *
     * @param string $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['auth.driver'] = $name;
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['auth.driver'];
    }
}